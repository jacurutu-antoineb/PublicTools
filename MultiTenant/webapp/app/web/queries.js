const q_listPostureScores = {
  query: `
    query listPostureScores(
      $interval: ListPostureScoresQueryInterval,
      $filter: PostureScoreFilter
    ) {
      listPostureScores: listPostureScores(interval: $interval, filter: $filter) {
        cursor
        scores {
          start_datetime
          end_datetime
          score {
            value
            global_average
            global_percentile
            __typename
          }
          changes {
            value
            degradations {
              value
              counts
              __typename
            }
            improvements {
              value
              counts
              __typename
            }
            __typename
          }
          __typename
        }
        __typename
      }
    }
  `,
  variables: {
    interval: "DAILY",
    filter: {
      AND: [
        {
          datetime: {
            GTE: "2024-06-07T06:00:00.000Z"
          }
        }
      ]
    }
  }
};



const q_getPostureFailures = {
	query:`
query GetPostureRuleAggregates(
  $limit: AggregationLimit
  $filter: PostureRuleFilter
  $group_by: [PostureRuleColumn!]
  $order_by: [PostureRuleColumnOrder!]
) {
  getPostureRuleAggregates(
    limit: $limit
    filter: $filter
    group_by: $group_by
    order_by: $order_by
  ) {
    attributes
    counts
    __typename
  }
}`,
	variables: `
	{
  "filter": {
    "AND": [
      {
        "is_passing": {
          "IS": false
        }
      },
      {
        "risk_level": {
          "NIN": [
            "INFORMATIVE",
            "LOW"
          ]
        }
      }
    ]
  },
  "limit": 50,
  "group_by": [
    "risk_level"
  ],
  "order_by": [
    {
      "column": "count",
      "is_desc": false
    }
  ]
}
`};
