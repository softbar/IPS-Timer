[![Version](https://img.shields.io/badge/IP--Symcon-Modul-red.svg?style=flat-square)](docs/readme_de.md) [![Version](https://img.shields.io/badge/IP--Symcon-5.2-blue.svg?style=flat-square)](docs/readme_de.md) [![Code](https://img.shields.io/badge/PHP-7.0-blue.svg?style=flat-square)](docs/readme_de.md)

# Module Timer v1.0
- Author **Xaver Bauer**
- Version **1.0**
- Date **03/10/2020**

Creates and manages simple timers from a script

## Table of Contents
- [Introductions](#1-introductions)
- [Features](#2-features)
- [Requirements](#3-requirements)
- [Installation](#4-installation)
- [Function reference](#5-function-reference)
    - [TIMER_Add](#51-timer_add)
    - [TIMER_StartScript](#52-timer_startscript)
    - [TIMER_StartVariable](#53-timer_startvariable)
    - [TIMER_Exists](#54-timer_exists)
    - [TIMER_Start](#55-timer_start)
    - [TIMER_Stop](#56-timer_stop)
    - [TIMER_Remove](#57-timer_remove)
    - [TIMER_SetPermanent](#58-timer_setpermanent)
    - [TIMER_SetRepeats](#59-timer_setrepeats)
    - [TIMER_Get](#510-timer_get)
    - [TIMER_Set](#511-timer_set)
- [Examples](#6-examples)
    - [Work with permanent timers](#a-work-with-permanent-timers)
    - [Work with simple timers](#b-work-with-simple-timers)
    - [Manage timers](#c-manage-timers)

## 1. Introductions
How many times have I used it, such a simple function like turning it off after xx seconds and I have always tinkered with events.
Well, now I have built the "timer" module for it.
Admittedly, events from IPS can do a lot more, but I don't need this everywhere and personally prefer to work with scripts because this is simply clearer for me.

## 2. Features
The module **Timer** supports the following:
- create a timer
- stop a timer
- start a timer
- delete a timer

[At the beginning](#table-of-contents)

## 3. Requirements
- IP-Symcon v5.2

[At the beginning](#table-of-contents)

## 4. Installation
Installation via the Module Control from IP-Symcon with the following URL

```
https://github.com/softbar/IPS-Timer
```

[At the beginning](#table-of-contents)

## 5. Function reference
[At the beginning](#table-of-contents)

#### 51. TIMER_Add
Adds a new, deactivated, timer to the list

```php
TIMER_Add ( $TimerID, $Ident, $Seconds, $Repeats, $Permanent, $VariableID, $ScriptContent, $VariableEndValue, $VariableStartValue)
```

Parameter

| Name                | Type   | Description                                       
|---------------------|--------|----------------------------------------------------
| $TimerID            | int    | InstanceID of the Timer module                    
| $Ident              | string | Clear timer identification, freely selectable and is required for all calls!
| $Seconds            | int    | The period of time when the timer is triggered. The minimum is 5, the default is 120 seconds
| $Repeats            | int    | Number of repetitions of the timer, standard is 1 time
| $Permanent          | bool   | Defines whether the timer is retained and deactivated after the expiry or is deleted. The default is the delete after the last execution.
| $VariableID         | int    | ObjectID of the variable to be switched, "bool", "int" and "float" are permitted
| $ScriptContent      | string | Either pure source code or the ObjectID an existing IPS script
| $VariableEndValue   | mixed  | The value that is passed to the variable or script when the timer is triggered
| $VariableStartValue | mixed  | The value passed to the variable or script when the timer starts, default zero, no value

Returns

| Type                | Description                                       
|---------------------|----------------------------------------------------
| boolean oder string | If successful, the function returns true, otherwise a string with the error.

>ScriptContent can source code without <? Php? > and with a final; contain or also the ID of an IPS script
>The timer must not exist with Add, otherwise the function returns an error message

[At the beginning](#table-of-contents)

#### 52. TIMER_StartScript
Changes and starts a permanent script timer or creates a new normal script timer and starts it once

```php
TIMER_StartScript ( $TimerID, $Ident, $Seconds, $ScriptContent, $VariableEndValue, $VariableStartValue)
```

Parameter

| Name                | Type   | Description                                       
|---------------------|--------|----------------------------------------------------
| $TimerID            | int    | InstanceID of the Timer module                    
| $Ident              | string | Clear timer identification, freely selectable and is required for all calls!
| $Seconds            | int    | The period of time when the timer is triggered. The minimum is 5, the default is 120 seconds
| $ScriptContent      | string | Either pure source code or the ObjectID an existing IPS script
| $VariableEndValue   | mixed  | The value that is passed to the variable or script when the timer is triggered
| $VariableStartValue | mixed  | The value passed to the variable or script when the timer starts, default zero, no value

Returns

| Type                | Description                                       
|---------------------|----------------------------------------------------
| boolean oder string | If successful, the function returns true, otherwise a string with the error.

>ScriptContent can source code without <? Php? > and with a final; contain or also the ID of an IPS script
>If the timer is available, it will be updated if it is a permanent timer and the parameter specified is NOT empty (0, '' or zero)

[At the beginning](#table-of-contents)

#### 53. TIMER_StartVariable
Changes and starts a permanent variable timer or creates a new normal variable timer and starts it once

```php
TIMER_StartVariable ( $TimerID, $Ident, $Seconds, $VariableID, $VariableEndValue, $VariableStartValue)
```

Parameter

| Name                | Type   | Description                                       
|---------------------|--------|----------------------------------------------------
| $TimerID            | int    | InstanceID of the Timer module                    
| $Ident              | string | Clear timer identification, freely selectable and is required for all calls!
| $Seconds            | int    | The period of time when the timer is triggered. The minimum is 5, the default is 120 seconds
| $VariableID         | int    | ObjectID of the variable to be switched, "bool", "int" and "float" are permitted
| $VariableEndValue   | mixed  | The value that is passed to the variable or script when the timer is triggered
| $VariableStartValue | mixed  | The value passed to the variable or script when the timer starts, default zero, no value

Returns

| Type                | Description                                       
|---------------------|----------------------------------------------------
| boolean oder string | If successful, the function returns true, otherwise a string with the error.

>If the timer is available, it will be updated if it is a permanent timer and the parameter specified is NOT empty (0, '' or zero)

[At the beginning](#table-of-contents)

#### 54. TIMER_Exists
Checks whether a timer already exists

```php
TIMER_Exists ( $TimerID, $Ident)
```

Parameter

| Name     | Type   | Description                                       
|----------|--------|----------------------------------------------------
| $TimerID | int    | InstanceID of the Timer module                    
| $Ident   | string | Clear timer identification, freely selectable and is required for all calls!

Returns

| Type    | Description                                       
|---------|----------------------------------------------------
| boolean | If $Ident is found, the function returns true, otherwise false.

[At the beginning](#table-of-contents)

#### 55. TIMER_Start
Restarts a permanent or stopped timer

```php
TIMER_Start ( $TimerID, $Ident, $Seconds)
```

Parameter

| Name     | Type   | Description                                       
|----------|--------|----------------------------------------------------
| $TimerID | int    | InstanceID of the Timer module                    
| $Ident   | string | Clear timer identification, freely selectable and is required for all calls!
| $Seconds | int    | The period of time when the timer is triggered. The minimum is 5, the default is 120 seconds

Returns

| Type                | Description                                       
|---------------------|----------------------------------------------------
| boolean oder string | If successful, the function returns true, otherwise a string with the error.

[At the beginning](#table-of-contents)

#### 56. TIMER_Stop
Stops the timer specified with $Ident

```php
TIMER_Stop ( $TimerID, $Ident, $SendEndEvent)
```

Parameter

| Name          | Type   | Description                                       
|---------------|--------|----------------------------------------------------
| $TimerID      | int    | InstanceID of the Timer module                    
| $Ident        | string | Clear timer identification, freely selectable and is required for all calls!
| $SendEndEvent | bool   | If true, the timer is executed again before the stops, regardless of the remaining time

Returns

| Type                | Description                                       
|---------------------|----------------------------------------------------
| boolean oder string | If successful, the function returns true, otherwise a string with the error

[At the beginning](#table-of-contents)

#### 57. TIMER_Remove
Stops and deletes the timer specified with $Ident from the list

```php
TIMER_Remove ( $TimerID, $Ident)
```

Parameter

| Name     | Type   | Description                                       
|----------|--------|----------------------------------------------------
| $TimerID | int    | InstanceID of the Timer module                    
| $Ident   | string | Clear timer identification, freely selectable and is required for all calls!

Returns

| Type                | Description                                       
|---------------------|----------------------------------------------------
| boolean oder string | If successful, the function returns true, otherwise a string with the error

[At the beginning](#table-of-contents)

#### 58. TIMER_SetPermanent
If a timer is set from permanent to non-permanent and the repetitions are already 0, it is deleted immediately
A permanent timer is not deleted after the repetitions have ended, but the repetitions and the time start again from the beginning. This means that the timer can simply start again from the beginning.

```php
TIMER_SetPermanent ( $TimerID, $Ident, $Permanent)
```

Parameter

| Name       | Type   | Description                                       
|------------|--------|----------------------------------------------------
| $TimerID   | int    | InstanceID of the Timer module                    
| $Ident     | string | Clear timer identification, freely selectable and is required for all calls!
| $Permanent | bool   | Permanent's new status                            

Returns

| Type                | Description                                       
|---------------------|----------------------------------------------------
| boolean oder string | If successful, the function returns true, otherwise a string with the error

>When a new timer is created, by default it is not permanent and is deleted after the repetitions have ended

[At the beginning](#table-of-contents)

#### 59. TIMER_SetRepeats
Changes the number of repetitions for an active or permanent timer

```php
TIMER_SetRepeats ( $TimerID, $Ident, $Repeats)
```

Parameter

| Name     | Type   | Description                                       
|----------|--------|----------------------------------------------------
| $TimerID | int    | InstanceID of the Timer module                    
| $Ident   | string | Clear timer identification, freely selectable and is required for all calls!
| $Repeats | int    | Number of repetitions of the timer, standard is 1 time

Returns

| Type                | Description                                       
|---------------------|----------------------------------------------------
| boolean oder string | If successful, the function returns true, otherwise a string with the error

[At the beginning](#table-of-contents)

#### 510. TIMER_Get
Returns an array with keys and values ​​of the timer

```php
TIMER_Get ( $TimerID, $Ident)
```

Parameter

| Name     | Type   | Description                                       
|----------|--------|----------------------------------------------------
| $TimerID | int    | InstanceID of the Timer module                    
| $Ident   | string | Clear timer identification, freely selectable and is required for all calls!

Returns

| Type  | Description                                       
|-------|----------------------------------------------------
| array | One with keys and values ​​of the timer named $Ident or an empty one if the timer was not found

[At the beginning](#table-of-contents)

#### 511. TIMER_Set
Sets an array of keys and values ​​of the timer

```php
TIMER_Set ( $TimerID, $Ident, $Timer)
```

Parameter

| Name     | Type   | Description                                       
|----------|--------|----------------------------------------------------
| $TimerID | int    | InstanceID of the Timer module                    
| $Ident   | string | Clear timer identification, freely selectable and is required for all calls!
| $Timer   | array  | An array of keys and values ​​of the timer to be changed

Returns

| Type                | Description                                       
|---------------------|----------------------------------------------------
| boolean oder string | If successful, the function returns true, otherwise a string with the error

>This is the only way to subsequently change a timer ident.
>Keys that cannot be used are removed before setting, invalid keys are ignored

[At the beginning](#table-of-contents)


## 6. Examples
#### a. Work with permanent timers
set the timers once with a script

```php
<?php
$TimerID = 51049; // ID meines Timer Moduls
$VariableID = 19272; // ID meiner Bool Variable STATE von einem HM Switch 

TIMER_Add ( $TimerID , $Ident='Mein Timer', $Seconds=60, $Repeats=1,$Permanent=true, $VariableID, $ScriptContent='', $VariableEndValue=false, $VariableStartValue=true);
TIMER_Add ( $TimerID , 'Mein Script Timer',120, 1,true, 0, 'echo 'Hallo ich bins';', null, null);

// nun kann in jedem Scrit oder im IPS Ereignis,als PHP code, der folgende Befehl eingegen werden
TIMER_Start( $TimerID, 'Mein Timer',0); // Sartet den Timer mit vorgabe 60 Sekunen , schaltet die $VariableID beim start auf $VariableStartValue (true) und beim beenden auf $VariableEndValue (false)
// oder
TIMER_Start( $TimerID, 'Mein Timer',120);// Sartet den Timer mit 120 Sekunen , alles andere wie oben
?>
```
#### b. Work with simple timers
Start a simple timer in any script

```php
<?php
$TimerID = 51049; // ID meines Timer Moduls
$VariableID = 19272; // ID meiner Bool Variable STATE von einem HM Switch 

TIMER_StartScript	( $TimerID, 'mein flur timer', 300, "echo 'Hallo';" , null,null);
//oder einen Timer nach 240 sek ausschalten
TIMER_StartVariable	( $TimerID, 'mein Bad timer', 240, $VariableID , false,true); 
?>
```
#### c. Manage timers
A timer can always be stopped, started or deleted before the time expires or if it is permanent

```php
<?php
$TimerID = 51049; // ID meines Timer Moduls
$VariableID = 19272; // ID meiner Bool Variable STATE von einem HM Switch 

TIMER_Stop( $TimerID, 'mein Bad timer',false); // anhalten
TIMER_Start($TimerID,  'mein Bad timer',0 );// fortsetzen
TIMER_Remove( $TimerID, 'mein Bad timer',true); // löschen
?>
```

[At the beginning](#table-of-contents)
