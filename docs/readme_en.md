<a href="https://www.symcon.de"><img src="https://img.shields.io/badge/IP--Symcon-5.1-blue.svg?style=flat-square"/></a>
<a href="https://styleci.io/repos/116687340/"><img src="https://styleci.io/repos/116687340/shield" alt="StyleCI"></a>
<a href="https://travis-ci.org/symcon/Alexa"><img src="https://img.shields.io/travis/symcon/Alexa/master.svg?style=flat-square" alt="Build status"></a>

# Module timer v1.0

Function:

Timer module
- Creator: Xavier
- Version ..: 1.0

**Table of Contents**
1. [Introduction](#1-introduction)
2. [Features](#2-features)
3. [Requirements](#3-requirements)
4. [Installation](#4-installation)
5. [Function Reference](#5-function-reference)
    - [TIMER_Add](#50-timer_add)
    - [TIMER_StartScript](#51-timer_startscript)
    - [TIMER_StartVariable](#52-timer_startvariable)
    - [TIMER_Exists](#53-timer_exists)
    - [TIMER_Start](#54-timer_start)
    - [TIMER_Stop](#55-timer_stop)
    - [TIMER_Remove](#56-timer_remove)
    - [TIMER_SetPermanent](#57-timer_setpermanent)
    - [TIMER_SetRepeats](#58-timer_setrepeats)
    - [TIMER_Get](#59-timer_get)
    - [TIMER_Set](#510-timer_set)
6. [Examples](#6-examples)
7. [Appendix](#7-appendix)

## 1. Introduction

How many times have I used it, such a simple function like turning it off after xx seconds
and I always tinkered with events.
Well, now I have built the "timer" module for it.
Admittedly, you can do a lot more with events from IPS, but I do not need this everywhere and work, admittedly, I prefer to work with scripts because this is simply clearer for me.

## 2. Features

## 3. Requirements
 - IP-Synmcon 5.2
 
## 4. Installation
## 5. Function reference

### 50. TIMER_Add


Function:

Adds a new, deactivated, timer to the list

```php
  TIMER_Add (int $TimerID, string $Ident, int $Seconds, int $Repeats, bool $Permanent, int $VariableID, string $ScriptContent, $VariableEndValue, $VariableStartValue)
```

Parameter:
- ** $Ident ** Clear timer identification, freely selectable and is required for all calls!
- ** $Seconds ** The period of time when the timer is triggered. The minimum is 5, the default is 120 seconds
- ** $Repeats ** Number of repetitions of the timer, standard is 1 time
- ** $Permanent ** Defines whether the timer is retained and deactivated after the expiration, or whether it is deleted. The default is the delete after the last execution.
- ** $VariableID ** ObjectID of the variable to be switched, Boolean, eger and float are allowed
- ** $ScriptContent ** Either pure source code or the ObjectID of an existing IPS script
- ** $VariableEndValue ** The value that is passed to the variable or script when the timer is triggered
- ** $VariableStartValue ** The value that is passed to the variable or script when the timer starts, standard zero, no value

Returns: boolean | string If successful, the function returns true, otherwise a string with the error.

Annotation:
> - ScriptContent can source code without <? Php? > and with a final; contain or also the ID of an IPS script
> - The timer must not exist with Add, otherwise the function returns an error message

---

### 51. TIMER_StartScript


Function:

Changes and starts a permanent script timer or creates a new normal script timer and starts it once

```php
  TIMER_StartScript (int $TimerID, string $Ident, int $Seconds, string $ScriptContent, $VariableEndValue, $VariableStartValue)
```

Parameter:
- ** $Ident ** Clear timer identification, freely selectable and is required for all calls!
- ** $Seconds ** The period of time when the timer is triggered. The minimum is 5, the default is 120 seconds
- ** $ScriptContent ** Either pure source code or the ObjectID of an existing IPS script
- ** $VariableEndValue ** The value that is passed to the variable or script when the timer is triggered
- ** $VariableStartValue ** The value that is passed to the variable or script when the timer starts, standard zero, no value

Returns: boolean | string If successful, the function returns true, otherwise a string with the error.

Annotation:
> - ScriptContent can source code without **<?php ?>** and with a final **;** sign or also the ID of an IPS script
> - If the timer is available, it will be updated if it is a permanent timer and the parameter specified is NOT empty (** 0 **, ** '' ** or ** null **)

---

### 52. TIMER_StartVariable


Function:

Changes and starts a permanent variable timer or creates a new normal variable timer and starts it once

```php
  TIMER_StartVariable (int $TimerID, string $Ident, int $Seconds, int $VariableID, $VariableEndValue, $VariableStartValue)
```

Parameter:
- ** $Ident ** Clear timer identification, freely selectable and is required for all calls!
- ** $Seconds ** The period of time when the timer is triggered. The minimum is 5, the default is 120 seconds
- ** $VariableID ** ObjectID of the variable to be switched, Boolean, eger and float are allowed
- ** $VariableEndValue ** The value that is passed to the variable or script when the timer is triggered
- ** $VariableStartValue ** The value of the variable o at the start of the timer

Returns: boolean | string If successful, the function returns true, otherwise a string with the error.

Annotation:
> - If the timer is available, it will be updated if it is a permanent timer and the parameter specified is NOT empty (** 0 **, ** '' ** or ** null **)

---

### 53. TIMER_Exists


Function:

Checks whether a timer already exists

```php
  TIMER_Exists (int $TimerID, string $Ident)
```

Parameter:
- ** $Ident ** Clear timer identification, freely selectable and is required for all calls!

Returns: boolean If $Ident is found, the function returns true, otherwise false.

---

### 54. TIMER_Start


Function:

Restarts a permanent or stopped timer

```php
  TIMER_Start (int $TimerID, string $Ident, int $Seconds)
```

Parameter:
- ** $Ident ** Clear timer identification, freely selectable and is required for all calls!
- ** $Seconds ** The period of time when the timer is triggered. The minimum is 5, the default is 120 seconds

Returns: boolean | string If successful, the function returns true, otherwise a string with the error.

---

### 55. TIMER_Stop


Function:

Stops the timer specified with $Ident

```php
  TIMER_Stop (int $TimerID, string $Ident, bool $SendEndEvent)
```

Parameter:
- ** $Ident ** Clear timer identification, freely selectable and is required for all calls!
- ** $SendEndEvent ** If true, the timer is executed again before the stops, regardless of the remaining time

Returns: boolean | string If successful, the function returns true, otherwise a string with the error

---

### 56. TIMER_Remove


Function:

Stops and deletes the timer specified with $Ident from the list

```php
  TIMER_Remove (int $TimerID, string $Ident)
```

Parameter:
- ** $Ident ** Clear timer identification, freely selectable and is required for all calls!

Returns: boolean | string If successful, the function returns true, otherwise a string with the error

---
### 57. TIMER_SetPermanent


Function:

If a timer is set from permanent to non-permanent and the repetitions are already 0, it is deleted immediately

A permanent timer is not deleted after the repetitions have ended, but the repetitions and the time start again from the beginning. This means that the timer can simply start again from the beginning.

```php
  TIMER_SetPermanent (int $TimerID, string $Ident, bool $Permanent)
```

Parameter:
- ** $Ident ** Clear timer identification, freely selectable and is required for all calls!
- ** $Permanent ** The new status of Permanent

Returns: boolean | string If successful, the function returns true, otherwise a string with the error

Annotation:
> - If a new timer is created, by default it is not permanent and is deleted after the repetitions have ended

---

### 58. TIMER_SetRepeats


Function:

Changes the number of repetitions for an active or permanent timer

```php
  TIMER_SetRepeats (int $TimerID, string $Ident, int $Repeats)
```

Parameter:
- ** $Ident ** Clear timer identification, freely selectable and is required for all calls!
- ** $Repeats ** Number of repetitions of the timer, standard is 1 time

Returns: boolean | string If successful, the function returns true, otherwise a string with the error

---

### 59. TIMER_Get


Function:

Returns an array with keys and values ​​of the timer

```php
  TIMER_Get (int $TimerID, string $Ident)
```

Parameter:
- ** $Ident ** Clear timer identification, freely selectable and is required for all calls!

The result is: array An array with keys and values ​​of the timer named $Ident or an empty array if the timer was not found

---

### 510. TIMER_Set


Function:

Sets an array of keys and values ​​of the timer

```php
  TIMER_Set (int $TimerID, string $Ident, array $Timer)
```

Parameter:
- ** $Ident ** Clear timer identification, freely selectable and is required for all calls!
- ** $Timer ** An array with keys and values ​​of the timer to be changed

Returns: boolean | string If successful, the function returns true, otherwise a string with the error

Annotation:
> - This is the only way to subsequently change a timer ident.
> - Keys that cannot be used are removed before setting, invalid keys are ignored

---
## 6. Examples
## 7. Appendix