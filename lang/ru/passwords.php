<?php

declare(strict_types=1);
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Языковые ресурсы напоминания пароля
    |--------------------------------------------------------------------------
    |
    | Последующие языковые строки возвращаются брокером паролей на неудачные
    | попытки обновления пароля в таких случаях, как ошибочный код сброса
    | пароля или неверный новый пароль.
    |
    */

    'password' => 'Пароль должен быть не короче шести символов и совпадать с подтверждением.',
    'reset'    => 'Ваш пароль был сброшен!',
    'sent'     => 'Спасибо! Если указанный e-mail существует в системе, ссылка для сброса пароля будет отправлена.',
    'token'    => 'Этот токен сброса пароля недействителен.',
    'user'     => 'Пользователь с таким e-mail не найден.',
];
