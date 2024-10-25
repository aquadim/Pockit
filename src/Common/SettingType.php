<?php
// Перечисление всех ID настроек

namespace Pockit\Common;

enum SettingType : int {
    case ActiveThemeId = 1;
    case WelcomeSetupCompleted = 2;
    case UserName = 3;
    case JournalLogin = 4;
    case JournalPassword = 5;
    case JournalPeriodId = 6;
    case AgstGroup = 7;
    case AgstCode = 8;
    case AgstSurname = 9;
    case AgstFull = 10;
    case AgstUseGostTypeB = 11;
    case AgstNamingTemplate = 12;
}