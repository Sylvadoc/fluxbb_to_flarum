<?php

WriteInLog('###########################################');
WriteInLog('### [6/9] Topic subscriptions migration ###');
WriteInLog('###########################################');

$query = RunQuery($dbFluxbb, "SELECT * FROM ${dbFluxbbPrefix}topic_subscriptions");
$subscriptions = $query->fetchAll(PDO::FETCH_ASSOC);

WriteInLog('Migrating ' . $query->rowCount() . ' topic subscriptions...');
$subscriptionsMigrated = 0;

foreach ($subscriptions as $subscription) {

    $subscriptionData = [
        ':user_id' => $subscription['user_id'],
        ':discussion_id' => $subscription['topic_id'],
        ':subscription' => 'follow'
    ];

    $query = RunPreparedQuery($dbFlarum, $subscriptionData, "INSERT INTO ${dbFlarumPrefix}users_discussions(user_id, discussion_id, subscription) VALUES(:user_id, :discussion_id, :subscription)");
    $subscriptionsMigrated += $query->rowCount();

}

WriteInLog('Done, results :');
WriteInLog("$subscriptionsMigrated topic subscriptions migrated successfully", 'SUCCESS');
