[![Version](https://img.shields.io/badge/IP--Symcon-Modul-red.svg?style=flat-square)](docs/readme_de.md) [![Version](https://img.shields.io/badge/IP--Symcon-5.2-blue.svg?style=flat-square)](docs/readme_de.md) [![Code](https://img.shields.io/badge/PHP-7.0-blue.svg?style=flat-square)](docs/readme_de.md)

# Modul Timer v1.0
- Author **Xaver Bauer**
- Version **1.0**
- Date **10.03.2020**

Erstellt einfache Timer aus einem Skript und verwaltet diese

## Inhaltsverzeichnis
- [Einleitung](#1-einleitung)
- [Funktionsumfang](#2-funktionsumfang)
- [Voraussetzungen](#3-voraussetzungen)
- [Installation](#4-installation)
- [Funktionsreferenz](#5-funktionsreferenz)
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
- [Beispiele](#6-beispiele)
    - [Mit Permanenten Timern arbeiten](#a-mit-permanenten-timern-arbeiten)
    - [Mit einfachen Timern arbeiten](#b-mit-einfachen-timern-arbeiten)
    - [Timer verwalten](#c-timer-verwalten)

## 1. Einleitung
Wie oft habe ich es gebraucht, so eine einfache Funktion wie ausschalten nach xx Sekunden und immer habe ich irgendwie mit Ereignissen gebastelt.

Nun, jetzt habe ich mir dafür das "Timer" Modul gebaut.

Zugegeben mit Ereignissen von IPS lässt sich viel mehr machen, dies brauche ich jedoch nicht überall außerdem arbeite ich persönlivh lieber mit Skripten da dies für mich einfach übersichtlicher ist.

## 2. Funktionsumfang
Das Modul **Timer** unterstützt folgendes:
- einen Timer erstellen
- einen Timer stoppen
- einen Timer starten
- einen Timer löschen

[Zum Anfang](#inhaltsverzeichnis)

## 3. Voraussetzungen
- IP-Symcon v5.2

[Zum Anfang](#inhaltsverzeichnis)

## 4. Installation
Installation über das Module Control von IP-Symcon mit folgender URL

```
https://github.com/softbar/IPS-Timer
```

[Zum Anfang](#inhaltsverzeichnis)

## 5. Funktionsreferenz
[Zum Anfang](#inhaltsverzeichnis)

#### 51. TIMER_Add
Fügt der Liste einen neuen, deaktivierten, Timer hinzu

```php
TIMER_Add ( $TimerID, $Ident, $Seconds, $Repeats, $Permanent, $VariableID, $ScriptContent, $VariableEndValue, $VariableStartValue)
```

Parameter

| Name                | Typ    | Beschreibung                                      
|---------------------|--------|----------------------------------------------------
| $TimerID            | int    | InstanceID des Timer Moduls                       
| $Ident              | string | Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
| $Seconds            | int    | Die Zeitspanne wann der Timer ausgelößt wird. Minimum sind 5 , Standard 120 Sekunden
| $Repeats            | int    | Anzahl der Wiederholungen des Timers, Standard ist 1 mal
| $Permanent          | bool   | Legt fest, ob der Timer nach Ablauf beibehalten und deaktiert, oder gelöscht wird. Standard ist das löschen nach der letzten Ausführung.
| $VariableID         | int    | ObjectID der zu schaltenden Variable, erlaubt sind "bool", "int" und "float"
| $ScriptContent      | string | Entweder reiner Quellkode oder die ObjectID eine vorhandenen IPS Skript
| $VariableEndValue   | mixed  | Der Wert der beim auslößen des Timers an die Variable oder das Skript übergeben wird
| $VariableStartValue | mixed  | Der Wert der beim Start des Timers an die Variable oder das Skript übergeben wird, Standard null , kein Wert

Rückgabe

| Typ                 | Beschreibung                                      
|---------------------|----------------------------------------------------
| boolean oder string | Bei Erfolg liefert die Funktion true, andernfalls einen String mit dem Fehler.

>ScriptContent kann den Quellcode ohne < ?php ? > und mit abschließendem ; enthalten oder auch die ID eines IPS Skript
>Mit Add darf der Timer nicht existieren andernfalls liefert die Funktion eine Fehlermeldung

[Zum Anfang](#inhaltsverzeichnis)

#### 52. TIMER_StartScript
Ändert und startet einen permanenten Skript Timer oder erstellt einen neuen normalen Skript Timer und startet diesen einmalig

```php
TIMER_StartScript ( $TimerID, $Ident, $Seconds, $ScriptContent, $VariableEndValue, $VariableStartValue)
```

Parameter

| Name                | Typ    | Beschreibung                                      
|---------------------|--------|----------------------------------------------------
| $TimerID            | int    | InstanceID des Timer Moduls                       
| $Ident              | string | Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
| $Seconds            | int    | Die Zeitspanne wann der Timer ausgelößt wird. Minimum sind 5 , Standard 120 Sekunden
| $ScriptContent      | string | Entweder reiner Quellkode oder die ObjectID eine vorhandenen IPS Skript
| $VariableEndValue   | mixed  | Der Wert der beim auslößen des Timers an die Variable oder das Skript übergeben wird
| $VariableStartValue | mixed  | Der Wert der beim Start des Timers an die Variable oder das Skript übergeben wird, Standard null , kein Wert

Rückgabe

| Typ                 | Beschreibung                                      
|---------------------|----------------------------------------------------
| boolean oder string | Bei Erfolg liefert die Funktion true, sonst einen String mit dem Fehler.

>ScriptContent kann den Quellcode ohne < ?php ? > und mit abschließendem ; enthalten oder auch die ID eines IPS Skript
>Ist der Timer vorhanden wird er aktualisiert wenn es ein Permanenter Timer ist und der jeweilig angegebene Parameter NICHT leer ( 0, '' oder null ) ist

[Zum Anfang](#inhaltsverzeichnis)

#### 53. TIMER_StartVariable
Ändert und startet einen permanenten Variable Timer oder erstellt einen neuen normalen Variable Timer und startet diesen einmalig

```php
TIMER_StartVariable ( $TimerID, $Ident, $Seconds, $VariableID, $VariableEndValue, $VariableStartValue)
```

Parameter

| Name                | Typ    | Beschreibung                                      
|---------------------|--------|----------------------------------------------------
| $TimerID            | int    | InstanceID des Timer Moduls                       
| $Ident              | string | Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
| $Seconds            | int    | Die Zeitspanne wann der Timer ausgelößt wird. Minimum sind 5 , Standard 120 Sekunden
| $VariableID         | int    | ObjectID der zu schaltenden Variable, erlaubt sind "bool", "int" und "float"
| $VariableEndValue   | mixed  | Der Wert der beim auslößen des Timers an die Variable oder das Skript übergeben wird
| $VariableStartValue | mixed  | Der Wert der beim Start des Timers an die Variable oder das Skript übergeben wird, Standard null , kein Wert

Rückgabe

| Typ                 | Beschreibung                                      
|---------------------|----------------------------------------------------
| boolean oder string | Bei Erfolg liefert die Funktion true, sonst einen String mit dem Fehler.

>Ist der Timer vorhanden wird er aktualisiert wenn es ein Permanenter Timer ist und der jeweilig angegebene Parameter NICHT leer ( 0, '' oder null ) ist

[Zum Anfang](#inhaltsverzeichnis)

#### 54. TIMER_Exists
Überprüft ob ein Timer bereits existiert

```php
TIMER_Exists ( $TimerID, $Ident)
```

Parameter

| Name     | Typ    | Beschreibung                                      
|----------|--------|----------------------------------------------------
| $TimerID | int    | InstanceID des Timer Moduls                       
| $Ident   | string | Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!

Rückgabe

| Typ     | Beschreibung                                      
|---------|----------------------------------------------------
| boolean | Wird $Ident gefunden liefert die Funktion true, sonst false.

[Zum Anfang](#inhaltsverzeichnis)

#### 55. TIMER_Start
Startet einen permanenten oder gestoppten Timer neu

```php
TIMER_Start ( $TimerID, $Ident, $Seconds)
```

Parameter

| Name     | Typ    | Beschreibung                                      
|----------|--------|----------------------------------------------------
| $TimerID | int    | InstanceID des Timer Moduls                       
| $Ident   | string | Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
| $Seconds | int    | Die Zeitspanne wann der Timer ausgelößt wird. Minimum sind 5 , Standard 120 Sekunden

Rückgabe

| Typ                 | Beschreibung                                      
|---------------------|----------------------------------------------------
| boolean oder string | Bei Erfolg liefert die Funktion true, sonst einen String mit dem Fehler.

[Zum Anfang](#inhaltsverzeichnis)

#### 56. TIMER_Stop
Stoppt den mit $Ident angegeben Timer

```php
TIMER_Stop ( $TimerID, $Ident, $SendEndEvent)
```

Parameter

| Name          | Typ    | Beschreibung                                      
|---------------|--------|----------------------------------------------------
| $TimerID      | int    | InstanceID des Timer Moduls                       
| $Ident        | string | Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
| $SendEndEvent | bool   | Wenn true wird vor den Stoppen, unabhängig von der Restlaufzeit, der Timer noch einmal ausgeführt

Rückgabe

| Typ                 | Beschreibung                                      
|---------------------|----------------------------------------------------
| boolean oder string | Bei Erfolg liefert die Funktion true, sonst einen String mit dem Fehler

[Zum Anfang](#inhaltsverzeichnis)

#### 57. TIMER_Remove
Stoppt und löscht den mit $Ident angegeben Timer aus der Liste

```php
TIMER_Remove ( $TimerID, $Ident)
```

Parameter

| Name     | Typ    | Beschreibung                                      
|----------|--------|----------------------------------------------------
| $TimerID | int    | InstanceID des Timer Moduls                       
| $Ident   | string | Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!

Rückgabe

| Typ                 | Beschreibung                                      
|---------------------|----------------------------------------------------
| boolean oder string | Bei Erfolg liefert die Funktion true, sonst einen String mit dem Fehler

[Zum Anfang](#inhaltsverzeichnis)

#### 58. TIMER_SetPermanent
Wird ein Timer von Permanent auf nicht Permanent gestellt und die Wiederholungen bereits 0 sind wird er sofort gelöscht
Ein Permanenter Timer wird nach Ablauf der Wiederholungen nicht gelöscht sondern die Wiederholungen, sowie die Zeit, starten wieder von Anfang an. Damit kann der Timer mit Start einfach wieder von vorn beginnen.

```php
TIMER_SetPermanent ( $TimerID, $Ident, $Permanent)
```

Parameter

| Name       | Typ    | Beschreibung                                      
|------------|--------|----------------------------------------------------
| $TimerID   | int    | InstanceID des Timer Moduls                       
| $Ident     | string | Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
| $Permanent | bool   | Der neue Status von Permanent                     

Rückgabe

| Typ                 | Beschreibung                                      
|---------------------|----------------------------------------------------
| boolean oder string | Bei Erfolg liefert die Funktion true, sonst einen String mit dem Fehler

>Wenn ein neuer Timer erstellt wird ist er, per Vorgabe, nicht Permanent und wird nach Ablauf der Wiederholungen gelöscht

[Zum Anfang](#inhaltsverzeichnis)

#### 59. TIMER_SetRepeats
Ändert die Anzahl der Wiederholungen für einen aktiven oder permanenten Timer

```php
TIMER_SetRepeats ( $TimerID, $Ident, $Repeats)
```

Parameter

| Name     | Typ    | Beschreibung                                      
|----------|--------|----------------------------------------------------
| $TimerID | int    | InstanceID des Timer Moduls                       
| $Ident   | string | Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
| $Repeats | int    | Anzahl der Wiederholungen des Timers, Standard ist 1 mal

Rückgabe

| Typ                 | Beschreibung                                      
|---------------------|----------------------------------------------------
| boolean oder string | Bei Erfolg liefert die Funktion true, sonst einen String mit dem Fehler

[Zum Anfang](#inhaltsverzeichnis)

#### 510. TIMER_Get
Liefert ein Array mit Schlüsseln und Werten des Timers

```php
TIMER_Get ( $TimerID, $Ident)
```

Parameter

| Name     | Typ    | Beschreibung                                      
|----------|--------|----------------------------------------------------
| $TimerID | int    | InstanceID des Timer Moduls                       
| $Ident   | string | Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!

Rückgabe

| Typ   | Beschreibung                                      
|-------|----------------------------------------------------
| array | Ein  mit Schlüsseln und Werten des Timers names $Ident oder ein leeres  wenn der Timer nicht gefunden wurde

[Zum Anfang](#inhaltsverzeichnis)

#### 511. TIMER_Set
Setzt ein Array mit Schlüsseln und Werten des Timers

```php
TIMER_Set ( $TimerID, $Ident, $Timer)
```

Parameter

| Name     | Typ    | Beschreibung                                      
|----------|--------|----------------------------------------------------
| $TimerID | int    | InstanceID des Timer Moduls                       
| $Ident   | string | Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
| $Timer   | array  | Ein Array mit Schlüsseln und Werten des Timers der geändert werden soll

Rückgabe

| Typ                 | Beschreibung                                      
|---------------------|----------------------------------------------------
| boolean oder string | Bei Erfolg liefert die Funktion true, sonst einen String mit dem Fehler

>Nur hiermit ist es nachträglich möglich einen Timer Ident zu ändern.
>Schlüssel die nicht verwendet werden können vor dem setzten entfernt werden, ungültige Schlüssel werden ignoriert

[Zum Anfang](#inhaltsverzeichnis)


## 6. Beispiele
#### a. Mit Permanenten Timern arbeiten
mit einem Skript einmalig die Timer setzen

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
#### b. Mit einfachen Timern arbeiten
In einem beliebigen Script einen einfachen Timer starten

```php
<?php
$TimerID = 51049; // ID meines Timer Moduls
$VariableID = 19272; // ID meiner Bool Variable STATE von einem HM Switch 

TIMER_StartScript	( $TimerID, 'mein flur timer', 300, "echo 'Hallo';" , null,null);
//oder einen Timer nach 240 sek ausschalten
TIMER_StartVariable	( $TimerID, 'mein Bad timer', 240, $VariableID , false,true); 
?>
```
#### c. Timer verwalten
Ein Timer kann immer vor dem Zeit-Ablauf , oder wenn er Permanent ist, angehalten, gestartet oder gelöscht werden

```php
<?php
$TimerID = 51049; // ID meines Timer Moduls
$VariableID = 19272; // ID meiner Bool Variable STATE von einem HM Switch 

TIMER_Stop( $TimerID, 'mein Bad timer',false); // anhalten
TIMER_Start($TimerID,  'mein Bad timer',0 );// fortsetzen
TIMER_Remove( $TimerID, 'mein Bad timer',true); // löschen
?>
```

[Zum Anfang](#inhaltsverzeichnis)
