# Laravel Lineage
A package for chain calling Laravel relationships. Some relationships return collections so can't be chained. This package provides a way to get past that.

## Installation
Install with composer

`composer require zerochip/laravel-lineage`

Add the ServiceProvider to your providers array in `config/app.php`

```php
'providers' => [
    // Other Service Providers

    Zerochip\LineageServiceProvider::class,
];
```

## Quickstart

Add the lineage trait to your model

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Zerochip\Lineage\LineageTrait

class Subject extends Model
{
    use LineageTrait;
}
```
For example to get a quizzes for a subject.

Without lineage we would have had to do this:

```php
$subject = Subject::find(1);
$quizzes = [];

foreach ($subject->courses as $course) {

    foreach ($course->chapters as $chapter) {

        foreach($chapter->topics as $topic) {

            foreach($topic->sections as $section) {
               
                $quizzes = $quizzes->merge($section->quizzes);
            }
        }
    }
}
```
Using Laravel-Lineage we just do the following

```php
$subject = Subject::find(1);
$quizzes = $subject->lineage('courses->chapters->topics->sections->quizzes');
```
You can also get the lineage for a collection.

```php
$subjects = Subject::get();
$quizzes = $subjects->lineage('courses->chapters->topics->sections->quizzes');
```

We can even get the inverse  of that
```php
$quiz = Quiz::find(1);
$subject = $quiz->lineage('section->topic->chapter->course->subject')->first();

```

**NB: lineage always returns a collection hence the use of first in the above**

## Lineage format

**Lineage format rules are as follows:**

1) Uses the delimiter `->`.

2) Should be a string
    ```php
    $subject->lineage('course->chapters');
    
    // the following won't work
    $subject->lineage(course->chapters);
    ```

3) Should have at least 2 levels
    ```php
    $subject->lineage('course->chapters');
    
    // the following will cause an error
    $subject->lineage('course');
    ```
4) If preferred can start with `->` 
    ```php
    $subject->lineage('course->chapters');
    
    // produces the same result as above
    $subject->lineage('->course->chapters');
    ```
5) Can not be open ended
    ```php
    $subject->lineage('course->chapters');
    
    // the following will cause an error
    $subject->lineage('course->chapters->');
    ```

## Usage without trait and collection macro

The lineage class can be used directly. This class has one static method `get()` which takes the object (model or collection) as the first parameter and the lineage as the second
```php
use Zerochip\Lineage;

$subject = Subject::find(1);
$topics = Lineage::get($subject, 'course->chapters->topics');

// The same with a collection
$courses = Courses::get();
$sections = Lineage::get($courses, 'chapters->topics->sections');
```
