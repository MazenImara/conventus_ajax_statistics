<?php
function conventus_ajax_statistics_views_data() {
  $data = [];

  $data['node_popular'] = [];

  $data['node_popular']['table'] = [];

  $data['node_popular']['table']['group'] = t('Popular nodes');

  $data['node_popular']['table']['provider'] = 'conventus_ajax_statistics';

  $data['node_popular']['table']['base'] = [
    'field' => 'nid',
    'title' => t('Popular table'),
    'help' => t('node popular table contains example content and can be related to nodes.'),
    'weight' => -10,
  ];

  $data['node_popular']['table']['join'] = [
    'node_field_data' => [
      'left_field' => 'nid',
      'field' => 'nid',
    ],
  ];

  $data['node_popular']['nid'] = [
    'title' => t('Example content'),
    'help' => t('Relate example content to the node content'),

    'relationship' => [
      'base' => 'node_field_data',
      'base field' => 'nid',
      'id' => 'standard',
      'label' => t('Example node'),
    ],
  ];

  $data['node_popular']['totalviews'] = [
    'title' => t('Custom total views'),
    'help' => t('sum of days views'),
    'field' => [
      'id' => 'popular_numeric',
      'click sortable' => TRUE,
     ],
    'filter' => [
      'id' => 'numeric',
    ],
    'argument' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['node_popular']['daycount'] = [
    'title' => t('Custom views today'),
    'help' => t('The total number of times the node has been viewed today.'),
    'field' => [
      'id' => 'popular_numeric',
      'click sortable' => TRUE,
     ],
    'filter' => [
      'id' => 'numeric',
    ],
    'argument' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['views']['area'] = [
    'title' => t('Text area'),
    'help' => t('Provide markup text for the area.'),
    'area' => [
      'id' => 'text',
    ],
  ];

  return $data;
}
