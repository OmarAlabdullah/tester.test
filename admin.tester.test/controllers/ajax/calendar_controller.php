<?php

function get_week($year = 0, $week_number = 0)
{
    $year = (int)$year;
    $week_number = (int)$week_number;

    $return = array(
        'succes' => false
    );

    if(!($year > 0))
        $year = (int)date('Y');
    $return['year'] = $year;

    if(!($week_number > 0))
        $week_number = (int)date('W');

    if($week_number > 52)
        $week_number = 1;

    $return['week_number'] = $week_number;


    $date = new DateTime('monday this week');

    if($year > 0 && $week_number > 0)
        $date->setISODate($year, $week_number);

    $return['monday'] = $date->format('d-m-Y');

    global $db;

    for($i = 1; $i <= 7; $i++)
    {
        $return['appointments'][$i] = array();
        $return['dates'][$i] = $date->format('d-m-Y');
        $return['highest_slot'][$i] = 0;

        $params = array(
            'clients' => array(
                'conditions' => array(
                    'appointment' => $date->format('Y-m-d'),
                    'archived' => '0000-00-00 00:00:00'
                ),
                'order' => 'appointment_slot'
            )
        );
        $clients = $db->select($params);
        if($clients)
        {
            //$return['appointments'][$i] = $clients;
            foreach($clients as $client)
            {
                $params = array(
                    'documents' => array(
                        'conditions' => array(
                            'archived' => '0000-00-00 00:00:00',
                            '(`client_id` = ' . (int)$client['Client']['id'] . ')',
                            'type' => 'nestor'
                        )
                    )
                );
                $nestors = $db->select($params);
                $params = array(
                    'documents' => array(
                        'conditions' => array(
                            'archived' => '0000-00-00 00:00:00',
                            'client_id' => (int)$client['Client']['id'],
                            'type' => 'dgt'
                        )
                    )
                );
                $dgt_documents = $db->select($params);

                $has_dgt = false;
                if($dgt_documents !== false && $dgt_documents !== null && count($dgt_documents) >= 3)
                {
                    $has_sterktebeproeving = false;
                    $has_dichtheidsbeproeving_nieuwe_leiding = false;
                    $has_dichtheidsbeproeving  = false;
                    foreach($dgt_documents as $dgt_document)
                    {
                        if(trim($dgt_document['Document']['subtype']) == 'sterktebeproeving')
                            $has_sterktebeproeving = true;
                        if(trim($dgt_document['Document']['subtype']) == 'dichtheidsbeproeving nieuwe leiding')
                            $has_dichtheidsbeproeving_nieuwe_leiding = true;
                        if(trim($dgt_document['Document']['subtype']) == 'dichtheidsbeproeving')
                            $has_dichtheidsbeproeving = true;
                    }
                    if($has_sterktebeproeving && $has_dichtheidsbeproeving_nieuwe_leiding && $has_dichtheidsbeproeving)
                        $has_dgt = true;
                }

                $return['appointments'][$i][] = array(
                    'Client' => $client['Client'],
                    'has_nestor' => ($nestors !== false && $nestors !== null && count($nestors) > 0),
                    'has_dgt' => $has_dgt
                );
            }

            foreach($clients as $client)
            {
                if($client['Client']['appointment_slot'] > $return['highest_slot'][$i])
                    $return['highest_slot'][$i] = (int)$client['Client']['appointment_slot'];
            }
        }

        $date->modify('+1 day');
    }

    $date->modify('-1 day');
    $return['sunday'] = $date->format('d-m-Y');

    $return['succes'] = true;

    //$date = new DateTime();
    //$date->setISODate($year, $week_number);

    $date->modify('-6 days');

    $return['notifications'] = array();
    for($i = 1; $i <= 7; $i++)
    {
        $params = array(
            'notifications' => array(
                'conditions' => array(
                    'date' => $date->format('Y-m-d'),
                    'archived' => '0000-00-00 00:00:00'
                ),
                'order' => 'slot'
            )
        );
        $notifications = $db->select($params);

        if($notifications)
            $return['notifications'][$date->format('d-m-Y')] = $notifications;

        $date->modify('+1 day');
    }

    //get date settings
    $return['date_settings'] = array();

    $params = array(
        'date_settings' => array(
            'conditions' => array(
                '(`date` >= "' . implode("-", array_reverse(explode("-", $return['monday']))) . '" AND `date` <= "' . implode("-", array_reverse(explode("-", $return['sunday']))) . '")'
            )
        )
    );
    $date_settings = $db->select($params);
    if(!is_null($date_settings) && $date_settings !== false)
    {
        foreach($date_settings as $date_setting)
        {
            $return['date_settings'][$date_setting['Date_setting']['date']] = $date_setting;
        }
    }

    print(json_encode($return, JSON_PRETTY_PRINT));
}

function select_slot($client_id = 0, $date = '', $slot = 0)
{
    $client_id = (int)$client_id;
    $slot = (int)$slot;

    $return = array(
        'succes' => false
    );

    if($client_id > 0 && strlen($date) == 10 && $slot > 0)
    {
        global $db;

        $client = $db->first('clients', $client_id);

        if($client)
        {
            if($client['Client']['appointment'] == '0000-00-00' || $client['Client']['appointment'] == '' || true)
            {
                //$return['query'] = "UPDATE `clients` SET `appointment` = '" . $date . "' AND `appointment_slot` = " . $slot . " WHERE `id` = " . $client['Client']['id'] . " LIMIT 1";
                $client['Client']['appointment'] = $date;
                $client['Client']['appointment_slot'] = $slot;
                //$client['Client']['timeframe_id'] = 0;

                $db->update($client);
                $return['mysql_error'] = mysqli_error($db->handle);

                $return['client'] = $client;
                if(empty($return['mysql_error']))
                {
                    $return['day_index'] = date('N', strtotime($date));
                    $return['succes'] = true;
                }
            }
        }
    }

    print(json_encode($return, JSON_PRETTY_PRINT));
}
function select_existing_slot($date = '', $slot = 0, $client_id = 0)
{
    $client_id = (int)$client_id;
    $slot = (int)$slot;

    $return = array(
        'succes' => false
    );

    if(strlen($date) == 10 && $slot > 0)
    {
        global $db;

        $params = array(
            'clients' => array(
                'conditions' => array(
                    'appointment' => $date,
                    'appointment_slot >= ' . $slot
                )
            )
        );
        $clients = $db->select($params);
        if($clients)
        {
            foreach($clients as $_client)
            {
                $_client['Client']['appointment_slot']++;
                $db->update($_client);
            }
        }

        $params = array(
            'notifications' => array(
                'conditions' => array(
                    'date' => $date,
                    'slot >= ' . $slot
                ),
                'order' => 'slot'
            )
        );
        $notifications = $db->select($params);
        //$return['notifications'] = $notifications;
        if($notifications)
        {
            foreach($notifications as $_notification)
            {
                $_notification['Notification']['slot'] += 1;
                $db->update($_notification);
            }
        }


        if($client_id > 0)
        {
            $client = $db->first('clients', $client_id);

            if($client)
            {
                $client['Client']['appointment'] = $date;
                $client['Client']['appointment_slot'] = $slot;
                //$client['Client']['timeframe_id'] = 0;
                $db->update($client);

                $return['mysql_error'] = mysqli_error($db->handle);

                $return['client'] = $client;
            }
        }

        $return['day_index'] = date('N', strtotime($date));
        $return['succes'] = true;
    }

    print(json_encode($return, JSON_PRETTY_PRINT));
}

function remove_slot($date = '', $slot = 0)
{
    $slot = (int)$slot;

    $return = array(
        'succes' => false
    );

    if(strlen($date) == 10 && $slot > 0)
    {
        global $db;

        $params = array(
            'clients' => array(
                'conditions' => array(
                    'appointment' => $date,
                    'appointment_slot' => $slot,
                    'archived' => '0000-00-00 00:00:00'
                ),
                'select' => 'first'
            )
        );
        $client = $db->select($params);

        if(!$client)
        {
            $return['can_remove'] = true;

            $params = array(
                'clients' => array(
                    'conditions' => array(
                        'appointment' => $date,
                        'appointment_slot >= ' . $slot
                    )
                )
            );
            $clients = $db->select($params);
            if($clients)
            {
                foreach($clients as $_client)
                {
                    $_client['Client']['appointment_slot']--;
                    $db->update($_client);
                }
                $return['succes'] = true;
            }

            $params = array(
                'notifications' => array(
                    'conditions' => array(
                        'date' => $date,
                        'slot >= ' . $slot
                    ),
                    'order' => 'slot'
                )
            );
            $notifications = $db->select($params);
            //$return['notifications'] = $notifications;
            if($notifications)
            {
                foreach($notifications as $_notification)
                {
                    $_notification['Notification']['slot'] -= 1;
                    $db->update($_notification);
                }
            }
        }
    }

    print(json_encode($return, JSON_PRETTY_PRINT));
}

function set_timeframe_id($client_id = 0, $timeframe_id)
{
    $client_id = (int)$client_id;
    $timeframe_id = (int)$timeframe_id;

    $return = array(
        'succes' => false
    );

    if($client_id > 0 && $timeframe_id > 0)
    {
        global $db;

        $client = $db->first('clients', $client_id);

        if($client)
        {
            $client['Client']['timeframe_id'] = $timeframe_id;
            $db->update($client);

            $return['succes'] = true;
        }
    }

    print(json_encode($return, JSON_PRETTY_PRINT));
}

function sort_timeframes()
{
    $return = array(
        'succes' => false
    );

    global $controller, $db;

    $return['post'] = $controller['post'];

    $sort = 1;

    foreach($controller['post']['sorted_ids'] as $timeframe_id)
    {
        $timeframe_id = (int)$timeframe_id;
        if($timeframe_id > 0)
        {
            $timeframe = $db->first('timeframes', $timeframe_id);
            if($timeframe)
            {
                $timeframe['Timeframe']['sort'] = $sort;
                $db->connect();
                $db->update($timeframe);
                $sort++;
                $return['succes'] = true;
            }
        }
    }

    print(json_encode($return, JSON_PRETTY_PRINT));
}
function remove_timeframes()
{
    global $controller, $db;

    $return = array(
        'succes' => false,
        'number_of_clients' => 0,
        'force' => $controller['post']['force']
    );

    $timeframes_ids = $controller['post']['timeframes_ids'];

    foreach($timeframes_ids as $timeframes_id)
    {
        $timeframe = $db->first('timeframes', $timeframes_id);
        if($timeframe)
        {
            $return['succes'] = true;

            $params = array(
                'clients' => array(
                    'conditions' => array(
                        'archived' => '0000-00-00 00:00:00',
                        'timeframe_id' => $timeframe['Timeframe']['id']
                    )
                )
            );
            $clients = $db->select($params);
            if($clients)
            {
                $return['number_of_clients'] += count($clients);
            }

            if(!$clients || $controller['post']['force'] == 'true')
            {
                $timeframe['Timeframe']['archived'] = date('Y-m-d H:i:s');
                $db->update($timeframe);

                if($clients)
                {
                    $db->query("UPDATE `clients` SET timeframe_id = 0 WHERE timeframe_id = " . $timeframe['Timeframe']['id']);
                }
            }
        }
    }

    print(json_encode($return));
}
function set_date_setting_slot(string $date = '', int $slots = 0)
{
    global $controller, $db;

    $return = array(
        'succes' => false
    );

    if(strlen($date) == 10 && $slots > 0)
    {
        $params = array(
            'date_settings' => array(
                'conditions' => array(
                    'date' => $date
                ),
                'select' => 'first'
            )
        );
        $date_setting = $db->select($params);

        if(!is_null($date_setting) && $date_setting !== false)
        {
            $date_setting['Date_setting']['slots'] = $slots;
            $db->update($date_setting);
            $return['succes'] = true;
        }else
        {
            $date_setting = array(
                'Date_setting' => array(
                    'date' => $date,
                    'slots' => $slots
                )
            );
            $insert_id = $db->insert($date_setting);
            if($insert_id > 0)
                $return['succes'] = true;
        }

        $return['date_setting'] = $date_setting;
    }

    print(json_encode($return));
}
function remove_date_setting(string $date = '')
{
    global $controller, $db;

    $return = array(
        'succes' => false
    );

    if(strlen($date) == 10)
    {
        $params = array(
            'date_settings' => array(
                'conditions' => array(
                    'date' => $date
                ),
                'select' => 'first'
            )
        );
        $date_setting = $db->select($params);

        if(!is_null($date_setting) && $date_setting !== false)
        {
            $db->query("DELETE FROM `date_settings` WHERE `id` = " . (int)$date_setting['Date_setting']['id'] . " LIMIT 1");
            $return['succes'] = true;
        }
    }

    print(json_encode($return));
}

function add_notification()
{
    global $controller, $db;

    $return = array(
        'succes' => false
    );

    $return['post'] = $controller['post'];

    $date = $controller['post']['date'];
    $slot = $controller['post']['slot'];
    $title = str_replace('"', '&quot;', str_replace('\'', '&lsquo;', $controller['post']['title']));
    $content = str_replace('"', '&quot;', str_replace('\'', '&lsquo;', $controller['post']['content']));

    if(strlen($title) >= 1 && strlen($date) == 10 && $slot > 0 && $slot <= 100)
    {
        $params = array(
            'clients' => array(
                'conditions' => array(
                    'appointment' => $date,
                    'appointment_slot' => $slot,
                    'archived' => '0000-00-00 00:00:00'
                ),
                'select' => 'first'
            )
        );
        $client = $db->select($params);
        if(!$client)
        {
            $params = array(
                'notifications' => array(
                    'conditions' => array(
                        'date' => $date,
                        'slot' => $slot,
                        'archived' => '0000-00-00 00:00:00'
                    ),
                    'select' => 'first'
                )
            );
            $notification = $db->select($params);
            if(!$notification)
            {
                $notification = array(
                    'Notification' => array(
                        'date' => $date,
                        'slot' => $slot,
                        'title' => $title,
                        'content' => $content,
                        'created' => date('Y-m-d H:i:s')
                    )
                );
                $insert_id = $db->insert($notification);

                if($insert_id > 0)
                    $return['succes'] = true;
            }
        }
    }

    print(json_encode($return));
}
function remove_notification(int $notification_id)
{
    global $controller, $db;

    $return = array(
        'succes' => false
    );

    if($notification_id > 0)
    {
        //$return['query'] = "DELETE FROM `notifications` WHERE `id` = " . $notification_id . " LIMIT 1";
        //$db->query($return['query']);

        $return['query'] = "UPDATE `notifications` SET `archived` = '" . date('Y-m-d H:i:s') . "' WHERE `id` = " . $notification_id . " LIMIT 1";
        $db->query($return['query']);

        $return['succes'] = true;
    }

    print(json_encode($return));
}
function remove_notifications()
{
    global $controller, $db;

    $return = array(
        'succes' => false
    );

    $notification_ids = $controller['post']['notification_ids'];
    foreach($notification_ids as $notification_id)
    {
        $notification_id = (int)$notification_id;

        if($notification_id > 0)
        {
            $db->query("UPDATE `notifications` SET `archived` = '" . date('Y-m-d H:i:s') . "' WHERE `id` = " . $notification_id . " LIMIT 1");
            $return['succes'] = true;
        }
    }

    print(json_encode($return));
}

?>