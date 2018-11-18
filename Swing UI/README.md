# Drawable with Swing UI
A java swing project providing a simple UI for drawable


# Why this project
To provide User interface for drawable

# How to run
- Create Jar file
	1. compile and create jar by running following command
		```
		javac Selector.java
		jar cvfe drawable.jar Selector *.class
		```
	2. or you can download jar from here: [drawable.jar](http://sabhayasaumil.com/archive/drawable.jar)
	
- run jar by following command: ``` java -jar drawable.jar```
- Convert/generate drawable resources by following below mentioned steps
	0. input resources: ![input resource](http://sabhayasaumil.com/archive/gui/drawable-resource-before.jpg)
	1. Browse directory containing drawable resource by clicking on browse button and select the input resource type. here in my case, input type will be xxhdpi as my resource directory is drawable-xxhdpi
	  - Example run: ![browse](http://sabhayasaumil.com/archive/gui/select-drawable-resource-and-the-type.jpg)
	2. hit resize and successful message/error will displayed to show success/error
		![Resize success](http://sabhayasaumil.com/archive/gui/message-when-resources-successfully-resized.jpg)
	3. output of the run: ![output](http://sabhayasaumil.com/archive/gui/drawable-resource-after-run.jpg)
	- Note: here, the program will ask you what kind of drawable resource type is the input. please select  correct option and make sure the drawable resource directory is named as per convention for selected resource type
