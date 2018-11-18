# Drawable CommandLine
A simple java script for Drawable


# Why this script
Its quick to run the script and can save all the hassle

# How to run
- Get path of the input directory which contains a drawable directory named as per Android standard naming convention.
	- For below example, path is D:\Photos\drawable demo\
	![input directory](http://sabhayasaumil.com/archive/how-to-run-drawable/command-line/Directory-containing-all-the-images.jpg)
- Download the script and compile it. to compile run: javac AndroidImageTransforner.java
- You can run the compiled class in two ways
	1. Input path can be passed as command line parameter. Make sure path is surrounded by double-qoutes if it contains spaces.
	  - Example run: 
	  ![Example run](http://sabhayasaumil.com/archive/how-to-run-drawable/command-line/Run-with-command-line-arg.jpg)
	2. You can run without passing command line argument and the program will ask you to pass the input path
	  - Example run: 
	  ![Example run](http://sabhayasaumil.com/archive/how-to-run-drawable/command-line/Run-without-commandline-arg.jpg)
	- Note: here, the program will ask you what kind of drawable resource type is the input. please select  correct option and make sure the drawable resource directory is named as per convention for selected resource type
- After the program run, all the required resources will be generated and will be placed under input directory.
	- ![Output](http://sabhayasaumil.com/archive/how-to-run-drawable/command-line/Directory-after-command-run.jpg)
	