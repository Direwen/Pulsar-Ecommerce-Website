<?php

function getSearchConditions(?string $search_attribute, ?string $record_search, ?string $record_search_end_date, ?string $record_search_number)
{
    $search_conditions = [];

    // Only add conditions if $search_attribute is not null
    if ($search_attribute !== null) {

        // Check if record_search is not null or both start and end dates are not null
        if ($record_search !== null) {
            $search_conditions[] = [
                'attribute' => $search_attribute,
                'value' => (strcasecmp($record_search, "false") === 0) ? "0" :
                          ((strcasecmp($record_search, "true") === 0) ? "1" : $record_search),
                'operator' => 'LIKE' // Specify the operator you want to use
            ];

            return $search_conditions;
        }

        if ($record_search_end_date !== null) {
            $search_conditions[] = [
                'attribute' => $search_attribute,
                'value' => $record_search_end_date,
                'operator' => '<='
            ];

            return $search_conditions;
        }
        
        if ($record_search_number !== null) {
            $search_conditions[] = [
                'attribute' => $search_attribute,
                'value' => $record_search_number,
                'operator' => '<='
            ];

            return $search_conditions;
        }
    }

    return $search_conditions; // Return empty array if no valid conditions
}