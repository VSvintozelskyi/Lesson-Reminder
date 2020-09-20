<?php
	require __DIR__."/google.php";
	$client = getClient($_GET['code']);
$service = new Google_Service_Calendar($client);

// Print the next 10 events on the user's calendar.
//TODO: paste correct calendar ID here
$calendarId = YOUR_CALENDAR_ID;
$optParams = array(
  'maxResults' => 10,
  'orderBy' => 'startTime',
  'singleEvents' => true,
  'timeMin' => date('c'),
);
$results = $service->events->listEvents($calendarId, $optParams);
$events = $results->getItems();

if (empty($events)) {
    echo "No upcoming events found.\n";
} else {
    echo "Upcoming events:\n";
    foreach ($events as $event) {
        $start = $event->start->dateTime;
        if (empty($start)) {
            $start = $event->start->date;
        }
        echo ( $event->getSummary() . $start . "<br>");
    }
}
?>