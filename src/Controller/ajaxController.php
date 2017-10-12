<?php

namespace Drupal\conventus_ajax_statistics\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

class ajaxController extends ControllerBase {
	/**
	 * Display the markup.
	 *
	 * @return array
	 */
	public function count($nid = NULL) {
		if ($nid != 0) {
			$node_counter = NULL;
			$timestamp    = REQUEST_TIME;
			$result       = \Drupal::database()->select('node_popular', 'q')
			                             ->fields('q', ['daycount'])
			                             ->condition('nid', $nid)
			                             ->execute();
			while ($row = $result->fetchAssoc()) {
				$node_counter = [
					'daycount' => $row['daycount'],
				];
			}
			if ($node_counter) {
				$node_counter['daycount']++;
				\Drupal::database()->update('node_popular')
				                   ->condition('nid', $nid)
				                   ->fields([
						'daycount'  => $node_counter['daycount'],
						'timestamp' => $timestamp,
					])
					->execute();
			} else {
				\Drupal::database()->insert('node_popular')
				                   ->fields(['nid', 'totalviews', 'daycount', 'timestamp'])
				                   ->values([$nid, 1, 1, $timestamp])
				                   ->execute();
			}
		}
		$period = \Drupal::config('conventus_ajax_statistics.settings')->get('period');
		return new JsonResponse(['value' => $period]);
	}
	static public function reset() {
		$period = \Drupal::config('conventus_ajax_statistics.settings')->get('period');
		$result = \Drupal::database()->select('node_popular', 'q')
		                             ->fields('q', ['nid', 'totalviews', 'daycount', 'timestamp', 'weeks'])
		                             ->execute();
		while ($row = $result->fetchAssoc()) {
			if ($row['weeks']) {
				$days = explode(':', $row['weeks']);
				unset($days[count($days) - 1]);
				if ($period < count($days)) {
					$row['weeks'] = '';
					for ($i=$period - 1; $i < count($days); $i++) {
						$row['weeks'] = $row['weeks'].$days[$i].':';
					}
					$days = explode(':', $row['weeks']);
					unset($days[count($days) - 1]);
				}
				if (count($days) < $period) {
					$row['weeks'] = $row['weeks'].$row['daycount'].':';
				} else {
					for ($i = 0; $i < count($days)-1; $i++) {
						$days[$i] = $days[$i+1];
					}
					$days[count($days)-1] = $row['daycount'];
					$row['weeks']         = '';
					foreach ($days as $day) {
						$row['weeks'] = $row['weeks'].$day.':';
					}
				}
				$days = explode(':', $row['weeks']);
				unset($days[count($days)-1]);
				$row['totalviews'] = 0;
				foreach ($days as $day) {
					$row['totalviews'] = $row['totalviews']+(int) $day;
				}
				$row['daycount'] = 0;
				self::save($row);
			} else {
				$row['weeks']      = $row['daycount'].':';
				$row['totalviews'] = $row['daycount'];
				$row['daycount']   = 0;
				self::save($row);
			}
		}
	}
	static public function save($node) {
		\Drupal::database()->update('node_popular')
		                   ->condition('nid', $node['nid'])
		                   ->fields([
				'totalviews' => $node['totalviews'],
				'daycount'   => $node['daycount'],
				'timestamp'  => $node['timestamp'],
				'weeks'      => $node['weeks'],
			])
			->execute();
		self::delete();
	}
	static public function delete() {
		\Drupal::database()->delete('node_popular', [])
			->condition('totalviews', 0)
			->execute();
	}
}
