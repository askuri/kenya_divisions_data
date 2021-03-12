# Administrative Divisions of Kenya Data

This repo contains geographic data about the Counties, Sub-Counties and Wards of Kenya according to the 2010 
constitution.

The data is based on a shapefile provided by the American Red Cross (ARC)
[here](https://data.humdata.org/dataset/administrative-wards-in-kenya-1450), licensed under Creative Commons Attribution
International (CC-BY). That shapefile is referred to as `arc` in this repository. It can be found in its original
state in the `data/arc_original` folder.

I'm not trained in geographic IT systems so bear with me for not using the correct terms and using obscure languages
like PHP (from a geography perspective). I DON'T GIVE ANY WARRANTY FOR THE CORRECTNESS OF THIS DATA.

## Data

### `arc_original`
Data as I got it from ARC originally, without modifications. Each ward is represented as a polygone.

### `arc_sanitized`
Geographical data is the same as in `arc_original`, but metadata / attributes like County and Ward names were cleaned
up:
- Put all names into *Title Case* with additional capitalization after each dash (-) and slash (/)
- Remove duplicate whitespaces (e.g. two or more spaces in a row)
- Remove "County", "Sub-county" (and its variations) and "Ward" from the values, as it's clear from the column name what
    it is
- Remove whitespaces at the left and right end of the values

### `arc_sanitizes_centroids`
Centroid of each ward's polygone together with the sanitization from `arc_sanitized`.

### The `tree` JSON file
While the shapefile is all good for working with GIS software, they may be difficult to deal with with some software
or programming languages.

The tree represents the hierarchy *County -> Sub-County -> Ward*. On the lowest level you will find the geographic data
/ shape / point. 

## Using the PHP script
Note: this is only of interest to you, if you want to modify the output data of the conversion. Basic knowledge of
coding or specifically of PHP is required.

1. Open [index.php](index.php) and set the input and output file at the top.
2. Download [composer](http://getcomposer.org).
3. Run `composer.phar install` inside this project directory.
4. To execute it, run `php index.php` where `php` is either in your PATH variable, or you give the path to your PHP
executable directly (often something like `C:\php\php.exe` on Windows).
