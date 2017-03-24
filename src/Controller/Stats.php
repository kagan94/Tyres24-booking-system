<?php
namespace Controller;

class Stats extends AbstractController
{
    private $_service;
    private $_cache;

    public function __construct()
    {
        parent::__construct();
        $this->_service = new \Service\Stats();

        $this->_cache = new \Doctrine\Common\Cache\PhpFileCache('cache');
    }

    public function defaultView()
    {
        if (!isLogged() || !isAdmin()){
            $this->permissionsErrorAndRedirect();
        }

        $get = $_GET;
        $filter = array();

        $monthlyStatsRecords = $this->_service->getMonthlyDynamics();

        // Apply filter
        // by default filter days by current week
        $filter['startDate'] = (isset($get['startDate']) && $get['startDate'])
            ? $get['startDate']
            : date('d.m.Y', strtotime( 'monday this week' ));
        $filter['endDate'] = (isset($get['endDate']) && $get['endDate'])
            ? $get['endDate']
            : date('d.m.Y', strtotime( 'sunday this week' ));

        $filter['bookingType'] = isset($get['bookingType']) ? $get['bookingType'] : null;

        $weeklyStatsRecords = $this->_service->getWeeklyStatistics($filter);

        /* Manipulations with weather */
        $key = 'weather_' . date('d_m_Y');
        $weatherInfo = $this->_cache->fetch($key);

        if ($weatherInfo === false){
            $days = array(
                'nextDay1' => date("D d.M"), // the weather doesn't make forecast more than 3 days ahead (incl)
                'nextDay2' => date("D d.M", strtotime('+1 day')),
                'nextDay3' => date("D d.M", strtotime('+2 days')),
                'nextDay4' => date("D d.M", strtotime('+3 days')));

            // Do request to update the weather data
            $forecast = $this->getWeatherForecast($days);

            if ($forecast === false){
                $this->addError('Unable to get information about the weather');
                $this->redirect($_GET['path']);
            }

            $weatherInfo = array_merge($days, $forecast);
            $this->_cache->save($key, $weatherInfo);
        }

        $this->setModel(array(
            'form_action' => '/stats',
            'monthlyStatsRecords' => $monthlyStatsRecords,
            'weeklyStatsRecords' => $weeklyStatsRecords,
            'weather' => $weatherInfo,
            'filter' => $filter
        ));
        return $this->display('stats/default.twig');
    }

    public function getWeatherForecast($days){
        $forecast = array(
            'markNextDay1' => false,
            'markNextDay2' => false,
            'markNextDay3' => false,
            'markNextDay4' => false
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
        curl_setopt($ch, CURLOPT_URL, "http://www.ilmateenistus.ee/ilma_andmed/xml/forecast.php?lang=eng");
        $xml_data = curl_exec($ch);
        curl_close($ch);

        // Make sure we actually have something to parse
        if($xml_data) {
            $parser = simplexml_load_string($xml_data);

            foreach ($parser->forecast as $el){
                $date = (string) $el->attributes()->date;

                foreach ($el as $key => $option){
                    foreach ($option->place as $place){
                        $stationName = (string) $place->name;
                        $forecastType = (string) $place->phenomenon;

                        // Closest station to Tallinn
                        if ($stationName == 'Harku' && ($forecastType == 'Heavy snowfall' || $forecastType == 'Heavy snow shower')){
                            $forecastDate = date("D d.M", strtotime($date));
                            $counter = 1;

                            foreach($days as $key => $day){
                                if ($day == $forecastDate){
                                    $forecast['markNextDay' . $counter] = true;
                                    // Go to the next date
                                    break(3);
                                }

                                $counter++;
                            }
                        }
                    }
                }
            }
        } else {
            $forecast = false;
        }

        return $forecast;
    }

    public function weatherAddWarning($dayNumber)
    {
        $key = 'weather_' . date('d_m_Y');
        $weatherInfo = $this->_cache->fetch($key);

        if ($weatherInfo !== false){
            $weatherInfo['markNextDay' . $dayNumber] = true;
            $this->_cache->save($key, $weatherInfo);
        }
        $this->redirect('/stats');
    }

    public function weatherRemoveWarning($dayNumber)
    {
        $key = 'weather_' . date('d_m_Y');
        $weatherInfo = $this->_cache->fetch($key);

        if ($weatherInfo !== false){
            $weatherInfo['markNextDay' . $dayNumber] = false;
            $this->_cache->save($key, $weatherInfo);
        }
        $this->redirect('/stats');
    }
}