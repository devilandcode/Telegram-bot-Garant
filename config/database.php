<?php

return [

// Database params
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'bot',
    'username' => 'root',
    'password' => '',

// Users Table
    'name_of_users_table' => 'users',
    'users_name_of_primary_key' => 'id',
    'users_name_of_column_with_id_telegram' => 'id_telegram',
    'users_name_of_column_with_username' => 'username',
    'users_name_of_column_with_id_chat' => 'chat_id',
    'users_name_of_column_with_moderate' => 'is_moderate',

// Search_history table
    'name_of_search_table' => 'search_history',
    'search_name_of_primary_key' => 'id',
    'search_name_of_column_with_id_buyer' => 'id_buyer',
    'search_name_of_column_with_id_seller' => 'id_seller',
    'search_name_of_column_with_crypto_amount' => 'amount',
    'search_name_of_column_with_crypto_currency' => 'currency',
    'search_name_of_column_with_terms_of_deal' => 'text',
    'search_name_of_column_with_start_search_time' => 'time_in',

// Deal table columns
    'name_of_deal_table' => 'deals',
    'deal_name_of_primary_key' => 'id',
    'deal_name_of_column_deal_id' => 'id_deal',
    'deal_name_of_column_with_id_buyer' => 'id_buyer',
    'deal_name_of_column_with_buyer_username' => 'buyer_username',
    'deal_name_of_column_with_id_seller' => 'id_seller',
    'deal_name_of_column_with_seller_username' => 'seller_username',
    'deal_name_of_column_with_crypto_amount' => 'amount',
    'deal_name_of_column_with_crypto_currency' => 'currency',
    'deal_name_of_column_with_crypto_cryptowallet' => 'cryptowallet',
    'deal_name_of_column_with_result_amount' => 'result_amount',
    'deal_name_of_column_with_terms_of_deal' => 'text',

];