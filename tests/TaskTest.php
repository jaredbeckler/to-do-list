<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Task.php";
    require_once "src/Category.php";

    $server = 'mysql:host=localhost:8889;dbname=to_do_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class TaskTest extends PHPUnit_Framework_TestCase
    {

        protected function tearDown()
        {
            Task::deleteAll();
            Category::deleteAll();
        }

        function testGetDescription()
        {
            // Arrange
            $description = "Wash the dog";
            $id = 1;
            $due_date = "2016-02-29";
            $test_task = new Task($description, $id, $due_date);
            $test_task->save();

            // Act
            $result = $test_task->getDescription();

            // Assert
            $this->assertEquals($description, $result);
        }

        function testSetDescription()
        {
            // Arrange
            $description = "Wash the dog";
            $id = 1;
            $due_date = "2016-02-29";
            $test_task = new Task($description, $id, $due_date);
            $test_task->save();

            // Act
            $test_task->setDescription("Feed the dog");
            $result = $test_task->getDescription();

            // Assert
            $this->assertEquals("Feed the dog", $result);
        }

        function testGetId()
        {
            //Arrange
            $description = "Wash the dog";
            $id = 1;
            $due_date = "2016-02-29";
            $test_task = new Task($description, $id, $due_date);
            $test_task->save();

            //Act
            $result = $test_task->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        function testSave()
        {
            //Arrange
            $description = "Wash the dog";
            $id = 1;
            $due_date = "2016-02-29";
            $test_task = new Task($description, $id, $due_date);

            //Act
            $test_task->save();

            //Assert
            $result = Task::getAll();
            $this->assertEquals($test_task, $result[0]);
        }

        function testSaveSetsId()
        {
            // Arrange
            $description = "Wash the dog";
            $id = 1;
            $due_date = "2016-02-29";
            $test_task = new Task($description, $id, $due_date);
            $test_task->save();

            // Act
            $test_task->save();

            // Assert
            $this->assertEquals(true, is_numeric($test_task->getId()));
        }

        function testGetAll()
        {
            //Arrange
            $description = "Wash the dog";
            $id = 1;
            $due_date = "2016-02-29";
            $test_task = new Task($description, $id, $due_date);
            $test_task->save();

            $description2 = "Water the lawn";
            $id2 = 2;
            $due_date2 = "2016-02-29";
            $test_task2 = new Task($description2, $id2, $due_date2);
            $test_task2->save();

            //Act
            $result = Task::getAll();

            //Assert
            $this->assertEquals([$test_task, $test_task2], $result);
        }

        function testDeleteAll()
        {
            //Arrange
            $description = "Wash the dog";
            $id = 1;
            $due_date = "2016-02-29";
            $test_task = new Task($description, $id, $due_date);
            $test_task->save();

            $description2 = "Water the lawn";
            $id2 = 2;
            $due_date2 = "2016-02-29";
            $test_task2 = new Task($description2, $id2, $due_date2);
            $test_task2->save();

            //Act
            Task::deleteAll();

            //Assert
            $result = Task::getAll();
            $this->assertEquals([], $result);
        }

        function testFind()
        {
            //Arrange
            $description = "Wash the dog";
            $id = 1;
            $due_date = "2016-02-29";
            $test_task = new Task($description, $id, $due_date);
            $test_task->save();

            $description2 = "Water the lawn";
            $id2 = 2;
            $due_date2 = "2016-02-29";
            $test_task2 = new Task($description2, $id2, $due_date2);
            $test_task2->save();

            //Act
            $result = Task::find($test_task->getId());

            //Assert
            $this->assertEquals($test_task, $result);
        }

        function testUpdate()
        {
            // Arrange
            $description = "Wash the dog";
            $id = 1;
            $due_date = "2016-02-29";
            $test_task = new Task($description, $id, $due_date);
            $test_task->save();

            $new_description = "Clean the dog";
            $new_due_date = "2016-02-28";

            // Act
            $test_task->update($new_description, $new_due_date);

            // Assert
            $this->assertEquals("Clean the dog", $test_task->getDescription());
        }

        function testDeleteTask()
        {
            // Arrange
            $name = "Home stuff";
            $id = 1;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "Wash the dog";
            $id2 = 2;
            $due_date = "2016-02-29";
            $test_task = new Task($description, $id2, $due_date);
            $test_task->save();

            // Act
            $test_task->addCategory($test_category);
            $test_task->delete();

            // Assert
            $this->assertEquals([], $test_category->getTasks());
        }

        function testAddCategory()
        {
            // Arrange
            $name = "Work stuff";
            $id = 1;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "Reply to emails";
            $id2 = 2;
            $due_date = "2016-02-28";
            $test_task = new Task($description, $id2, $due_date);
            $test_task->save();

            // Act
            $test_task->addCategory($test_category);

            // Assert
            $this->assertEquals($test_task->getCategories(), [$test_category]);
        }

        function testGetCategories()
        {
            // Arrange
            $name = "Work stuff";
            $id = 1;
            $test_category = new Category($name, $id);
            $test_category->save();

            $name2 = "Volunteer stuff";
            $id2 = 2;
            $test_category2 = new Category($name2, $id2);
            $test_category2->save();

            $description = "Reply to emails";
            $id3 = 3;
            $due_date = "2016-02-28";
            $test_task = new Task($description, $id3, $due_date);
            $test_task->save();

            // Act
            $test_task->addCategory($test_category);
            $test_task->addCategory($test_category2);

            // Assert
            $this->assertEquals($test_task->getCategories(), [$test_category, $test_category2]);
        }

    }
?>
