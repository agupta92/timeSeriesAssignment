# timeSeriesAssignment
It helps in generating low output Hours of Solar Panel through TimeSeries DB inFlux

API : To get hours in which Power generated is less than standard output
URL : http://ec2-52-24-15-41.us-west-2.compute.amazonaws.com/oorjan/timeSeriesAssignment/getPowerAnalysis.php?solar_id=1&date=02-02-2017
Parameters: solar_id = <id of solar device>
            date = <date of check>{dd-mm-yyyy} (currently sample is for 1st to 4th Jan 2017)
           
*****Other Files:******
1) insertYearlyPowerProduced.php : It will be used to insert standard power output for any longitude/latitude.
2) SamplePoints.php : Insert Sample influx data for any user ID and for any specific date.
3) config.php : It's having connection settings.
