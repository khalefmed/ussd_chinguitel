<?php

// Include the pthreads library
include 'Thread.php';

// Define the functions you want to run in separate threads
function function1() {
    // Code for the first function
    echo "Function 1 started\n";
    sleep(2); // Simulate some work
    echo "Function 1 finished\n";
}

function function2() {
    // Code for the second function
    echo "Function 2 started\n";
    sleep(3); // Simulate some work
    echo "Function 2 finished\n";
}

// Create thread objects for each function
$thread1 = new Thread('function1');
$thread2 = new Thread('function2');

// Start the threads
$thread1->start();
$thread2->start();

// Wait for the threads to finish
$thread1->join();
$thread2->join();

echo "All threads have finished\n";

?>

