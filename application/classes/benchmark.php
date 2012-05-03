<?php defined('DOCROOT') or die('No direct access allowed.');
/**
 * Test different implementations of a solution for their speed (and choose
 * the fastest).
 *
 * @package   PHPMVC
 * @category  Core
 * @author    T. Zengerink
 */
class Benchmark {

	/**
	 * Return a string containing info on the time and memory used since
	 * the application started running.
	 *
	 * @return  string  profiling information
	 * @uses    View
	 */
	public static function profile()
	{
		// Return the view
		return View::factory('benchmark/profile')
			->set('memory', (memory_get_usage() - START_MEMORY) / 1024)
			->set('time', round(microtime(TRUE) - START_TIME, 5))
			->set('memory_limit', 131072) // 128M
			->set('time_limit', 30)
			->render();
	}

	/**
	 * Get the Benchmark instance for the given name.
	 *
	 * @param   string     name
	 * @return  Benchmark  instance
	 */
	public static function factory($name)
	{
		return new Benchmark($name);
	}

	/**
	 * @var  array  method best count
	 */
	public $best = array();

	/**
	 * @var  string  best method
	 */
	public $best_method;

	/**
	 * @var  array  fastest runs
	 */
	public $fastest = array();

	/**
	 * @var  array  running times
	 */
	public $running_times = array();

	/**
	 * @var  Benchmark  instance
	 */
	protected $_instance;

	/**
	 * @var  array  methods
	 */
	protected $_methods;

	/**
	 * @var  ReflectionClass  reflection class
	 */
	protected $_reflection;

	/**
	 * Construct a Benchmark instance.
	 * Use the factory method instead.
	 *
	 * @param   string     name
	 * @return  Benchmark  instance
	 */
	public function __construct($name)
	{
		// Require requested benchmark class
		require_once(APPROOT.'benchmark/'.strtolower($name).EXT);

		// Set instances, reflection and methods
		$class             = 'Benchmark_'.ucwords($name);
		$this->_instance   = new $class;
		$this->_reflection = new ReflectionClass($class);
		$this->_methods    = $this->_reflection->getMethods(ReflectionMethod::IS_PUBLIC);
	}

	/**
	 * Results of all the functions in the requested class.
	 *
	 * @return  array  results
	 */
	public function results()
	{
		$results = array();
		foreach ($this->_methods as $method)
		{
			$results[$method->name] = $this->_instance->{$method->name}();
		}
		return $results;
	}

	/**
	 * Run the benchmark test on the requested class.
	 *
	 * @param   integer   step size
	 * @param   integer   end
	 * @param   boolean   determine which method is best
	 * @return  Benchmark
	 */
	public function run($step = 5000, $end = 100000, $determine_best = TRUE)
	{
		// Create information array
		for ($i = $step; $i < $end; $i += $step)
		{
			$this->runtimes['Runs / Method'][] = $i;	
			$this->fastest[$i]                 = 0;
		}

		// Loop all methods
		foreach ($this->_methods as $method)
		{
			// Set the time limit per method to max_execution_time
			set_time_limit(ini_get('max_execution_time'));

			// Execute the method X times
			for ($i = $step; $i < $end; $i += $step)
			{
				// Benchmark method execution
				$bench = microtime(TRUE);
				for ($j = 0; $j < $i; $j++)
				{
					$this->_instance->{$method->name}();
				}

				// Set runtimes & fastest
				$runtime                           = floor((microtime(TRUE) - $bench) * 1000);
				$this->runtimes[$method->name][$i] = $runtime;
				$this->fastest[$i]                 = ( ! $this->fastest[$i] OR $runtime < $this->fastest[$i]) ? $runtime : $this->fastest[$i];
			}
		}

		// Return this (after determining best method) for chainability
		if ($determine_best)
		{
			return $this->determine_best();
		}
		return $this;
	}

	/**
	 * Determine the best method to use.
	 *
	 * @return  Benchmark
	 */
	public function determine_best()
	{
		// Loop all methods and their runtimes
		foreach ($this->_methods as $method)
		{
			// Set best count to zero
			$this->best[$method->name] = 0;

			// Loop runtimes to see when was best
			foreach ($this->runtimes[$method->name] as $runs => $time)
			{
				if ($time === $this->fastest[$runs])
				{
					$this->best[$method->name]++;
				}
			}
		}

		// Check to see which method has highest count
		$max = array_keys($this->best, max($this->best));
		$this->best_method = $max[0];

		// Return this for chainability
		return $this;
	}

	/**
	 * Render the view that shows the runtimes, fastest runs and best method.
	 *
	 * @return  void
	 */
	public function render()
	{
		return View::factory('benchmark/overview')
			->set('runtimes', $this->runtimes)
			->set('fastest', $this->fastest)
			->set('best_method', $this->best_method)
			->render();
	}

} // End Benchmark
