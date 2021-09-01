/*!
 *  Lang.js for Laravel localization in JavaScript.
 *
 *  @version 1.1.10
 *  @license MIT https://github.com/rmariuzzo/Lang.js/blob/master/LICENSE
 *  @site    https://github.com/rmariuzzo/Lang.js
 *  @author  Rubens Mariuzzo <rubens@mariuzzo.com>
 */
(function(root,factory){"use strict";if(typeof define==="function"&&define.amd){define([],factory)}else if(typeof exports==="object"){module.exports=factory()}else{root.Lang=factory()}})(this,function(){"use strict";function inferLocale(){if(typeof document!=="undefined"&&document.documentElement){return document.documentElement.lang}}function convertNumber(str){if(str==="-Inf"){return-Infinity}else if(str==="+Inf"||str==="Inf"||str==="*"){return Infinity}return parseInt(str,10)}var intervalRegexp=/^({\s*(\-?\d+(\.\d+)?[\s*,\s*\-?\d+(\.\d+)?]*)\s*})|([\[\]])\s*(-Inf|\*|\-?\d+(\.\d+)?)\s*,\s*(\+?Inf|\*|\-?\d+(\.\d+)?)\s*([\[\]])$/;var anyIntervalRegexp=/({\s*(\-?\d+(\.\d+)?[\s*,\s*\-?\d+(\.\d+)?]*)\s*})|([\[\]])\s*(-Inf|\*|\-?\d+(\.\d+)?)\s*,\s*(\+?Inf|\*|\-?\d+(\.\d+)?)\s*([\[\]])/;var defaults={locale:"en"};var Lang=function(options){options=options||{};this.locale=options.locale||inferLocale()||defaults.locale;this.fallback=options.fallback;this.messages=options.messages};Lang.prototype.setMessages=function(messages){this.messages=messages};Lang.prototype.getLocale=function(){return this.locale||this.fallback};Lang.prototype.setLocale=function(locale){this.locale=locale};Lang.prototype.getFallback=function(){return this.fallback};Lang.prototype.setFallback=function(fallback){this.fallback=fallback};Lang.prototype.has=function(key,locale){if(typeof key!=="string"||!this.messages){return false}return this._getMessage(key,locale)!==null};Lang.prototype.get=function(key,replacements,locale){if(!this.has(key,locale)){return key}var message=this._getMessage(key,locale);if(message===null){return key}if(replacements){message=this._applyReplacements(message,replacements)}return message};Lang.prototype.trans=function(key,replacements){return this.get(key,replacements)};Lang.prototype.choice=function(key,number,replacements,locale){replacements=typeof replacements!=="undefined"?replacements:{};replacements.count=number;var message=this.get(key,replacements,locale);if(message===null||message===undefined){return message}var messageParts=message.split("|");var explicitRules=[];for(var i=0;i<messageParts.length;i++){messageParts[i]=messageParts[i].trim();if(anyIntervalRegexp.test(messageParts[i])){var messageSpaceSplit=messageParts[i].split(/\s/);explicitRules.push(messageSpaceSplit.shift());messageParts[i]=messageSpaceSplit.join(" ")}}if(messageParts.length===1){return message}for(var j=0;j<explicitRules.length;j++){if(this._testInterval(number,explicitRules[j])){return messageParts[j]}}var pluralForm=this._getPluralForm(number);return messageParts[pluralForm]};Lang.prototype.transChoice=function(key,count,replacements){return this.choice(key,count,replacements)};Lang.prototype._parseKey=function(key,locale){if(typeof key!=="string"||typeof locale!=="string"){return null}var segments=key.split(".");var source=segments[0].replace(/\//g,".");return{source:locale+"."+source,sourceFallback:this.getFallback()+"."+source,entries:segments.slice(1)}};Lang.prototype._getMessage=function(key,locale){locale=locale||this.getLocale();key=this._parseKey(key,locale);if(this.messages[key.source]===undefined&&this.messages[key.sourceFallback]===undefined){return null}var message=this.messages[key.source];var entries=key.entries.slice();var subKey="";while(entries.length&&message!==undefined){var subKey=!subKey?entries.shift():subKey.concat(".",entries.shift());if(message[subKey]!==undefined){message=message[subKey];subKey=""}}if(typeof message!=="string"&&this.messages[key.sourceFallback]){message=this.messages[key.sourceFallback];entries=key.entries.slice();subKey="";while(entries.length&&message!==undefined){var subKey=!subKey?entries.shift():subKey.concat(".",entries.shift());if(message[subKey]){message=message[subKey];subKey=""}}}if(typeof message!=="string"){return null}return message};Lang.prototype._findMessageInTree=function(pathSegments,tree){while(pathSegments.length&&tree!==undefined){var dottedKey=pathSegments.join(".");if(tree[dottedKey]){tree=tree[dottedKey];break}tree=tree[pathSegments.shift()]}return tree};Lang.prototype._applyReplacements=function(message,replacements){for(var replace in replacements){message=message.replace(new RegExp(":"+replace,"gi"),function(match){var value=replacements[replace];var allCaps=match===match.toUpperCase();if(allCaps){return value.toUpperCase()}var firstCap=match===match.replace(/\w/i,function(letter){return letter.toUpperCase()});if(firstCap){return value.charAt(0).toUpperCase()+value.slice(1)}return value})}return message};Lang.prototype._testInterval=function(count,interval){if(typeof interval!=="string"){throw"Invalid interval: should be a string."}interval=interval.trim();var matches=interval.match(intervalRegexp);if(!matches){throw"Invalid interval: "+interval}if(matches[2]){var items=matches[2].split(",");for(var i=0;i<items.length;i++){if(parseInt(items[i],10)===count){return true}}}else{matches=matches.filter(function(match){return!!match});var leftDelimiter=matches[1];var leftNumber=convertNumber(matches[2]);if(leftNumber===Infinity){leftNumber=-Infinity}var rightNumber=convertNumber(matches[3]);var rightDelimiter=matches[4];return(leftDelimiter==="["?count>=leftNumber:count>leftNumber)&&(rightDelimiter==="]"?count<=rightNumber:count<rightNumber)}return false};Lang.prototype._getPluralForm=function(count){switch(this.locale){case"az":case"bo":case"dz":case"id":case"ja":case"jv":case"ka":case"km":case"kn":case"ko":case"ms":case"th":case"tr":case"vi":case"zh":return 0;case"af":case"bn":case"bg":case"ca":case"da":case"de":case"el":case"en":case"eo":case"es":case"et":case"eu":case"fa":case"fi":case"fo":case"fur":case"fy":case"gl":case"gu":case"ha":case"he":case"hu":case"is":case"it":case"ku":case"lb":case"ml":case"mn":case"mr":case"nah":case"nb":case"ne":case"nl":case"nn":case"no":case"om":case"or":case"pa":case"pap":case"ps":case"pt":case"so":case"sq":case"sv":case"sw":case"ta":case"te":case"tk":case"ur":case"zu":return count==1?0:1;case"am":case"bh":case"fil":case"fr":case"gun":case"hi":case"hy":case"ln":case"mg":case"nso":case"xbr":case"ti":case"wa":return count===0||count===1?0:1;case"be":case"bs":case"hr":case"ru":case"sr":case"uk":return count%10==1&&count%100!=11?0:count%10>=2&&count%10<=4&&(count%100<10||count%100>=20)?1:2;case"cs":case"sk":return count==1?0:count>=2&&count<=4?1:2;case"ga":return count==1?0:count==2?1:2;case"lt":return count%10==1&&count%100!=11?0:count%10>=2&&(count%100<10||count%100>=20)?1:2;case"sl":return count%100==1?0:count%100==2?1:count%100==3||count%100==4?2:3;case"mk":return count%10==1?0:1;case"mt":return count==1?0:count===0||count%100>1&&count%100<11?1:count%100>10&&count%100<20?2:3;case"lv":return count===0?0:count%10==1&&count%100!=11?1:2;case"pl":return count==1?0:count%10>=2&&count%10<=4&&(count%100<12||count%100>14)?1:2;case"cy":return count==1?0:count==2?1:count==8||count==11?2:3;case"ro":return count==1?0:count===0||count%100>0&&count%100<20?1:2;case"ar":return count===0?0:count==1?1:count==2?2:count%100>=3&&count%100<=10?3:count%100>=11&&count%100<=99?4:5;default:return 0}};return Lang});

(function () {
    Lang = new Lang();
    Lang.setMessages({"de.text":{"apply":"best\u00e4tigen","apply_and_back_to_events":"Bewerben und zur\u00fcck zu Veranstaltungen","apply_and_close":"Bewerben und schlie\u00dfen","apr":"apr","april":"april","aug":"aug","august":"august","back_to_events":"zur\u00fcck zu Veranstaltungen","book":"buchen","book_an_event":"eine Veranstaltung buchen","bookings":"buchungen","cancel":"abbrechen","client":"kunde","clients":"kunden","close":"schlie\u00dfen","closed":"geschlossen","created":"erstellt","current":"aktuell","current_duration":"aktuelle Dauer","date":"datum","date_and_time":"Terminzeit","day":"tag","dec":"dez","december":"dezember","do_you_really_want_to_delete_this_event":"M\u00f6chten Sie diese Veranstaltung wirklich l\u00f6schen","duration":"dauer","edit_event":"Veranstaltung bearbeiten","enable_booking_on_any_date_time":"Aktivieren Sie die Buchung zu jedem Datum und zu jeder Uhrzeit","feb":"feb","february":"februar","filters":"filter","free_time":"Verf\u00fcgbare Zeit","free_time_on":"Freizeit an","fri":"fr","friday":"freitag","hall":"gesch\u00e4ft","halls":"gesch\u00e4fte","home":"home","jan":"jan","january":"januar","jul":"juli","july":"juli","jun":"juni","june":"juni","list":"auff\u00fchren","mar":"m\u00e4rz","march":"m\u00e4rz","may":"mai","mon":"mo","monday":"montag","month":"monat","more":"mehr","new_event":"neues Event","no":"nein","not_approved":"nicht best\u00e4tigt","nov":"nov","november":"november","oct":"okt","october":"oktober","ok":"ok","only_free_slots":"Nur freie Slots","pick":"w\u00e4hlen","pick_a_time_for_event":"W\u00e4hlen Sie eine Zeit f\u00fcr die Veranstaltung","pick_all_items_and_then_pick_a_time_for_event":"W\u00e4hlen Sie alle Elemente aus und w\u00e4hlen Sie dann eine Zeit f\u00fcr das Ereignis aus","pick_time":"Zeit ausw\u00e4hlen","please_select_all_filters":"Jetzt Termin ausw\u00e4hlen","reset":"r\u00fccksetzen","sat":"sa","saturday":"samstag","search":"suche","sep":"sept","sept":"sept","september":"september","settings":"einstellungen","sun":"so","sunday":"sonntag","suspended_halls":"geschlossen","suspended_workers":"inaktiv","temp":"dien","template":"dienstleistung","templates":"dienstleistung","thu":"do","thursday":"donnerstag","time":"zeit","title":"name","to_date":"to date","today":"heute","total":"gesamt","tue":"di","tuesday":"dienstag","view":"ansicht","wed":"mi","wednesday":"mittwoch","week":"woche","with_all_events":"Mit allen Veranstaltungen","with_events_per_client":"Mit Events pro Kunde","worker":"mitarbeiter","workers":"mitarbeiter","yes":"ja"},"en.auth":{"failed":"These credentials do not match our records.","password":"The provided password is incorrect.","throttle":"Too many login attempts. Please try again in :seconds seconds."},"en.pagination":{"next":"Next &raquo;","previous":"&laquo; Previous"},"en.passwords":{"reset":"Your password has been reset!","sent":"We have emailed your password reset link!","throttled":"Please wait before retrying.","token":"This password reset token is invalid.","user":"We can't find a user with that email address."},"en.text":{"apply":"apply","apply_and_back_to_events":"Apply, and back to events","apply_and_close":"Apply, and close","apr":"apr","april":"april","aug":"aug","august":"august","back_to_events":"back to events","book":"book","book_an_event":"book an event","bookings":"bookings","cancel":"cancel","client":"client","clients":"clients","close":"close","closed":"closed","created":"created","current":"current","current_duration":"current duration","date":"date","date_and_time":"Date&Time","day":"day","dec":"dec","december":"december","do_you_really_want_to_delete_this_event":"Do you really want to delete this event","duration":"duration","edit_event":"Edit event","enable_booking_on_any_date_time":"Enable booking on any date, time","feb":"feb","february":"february","filters":"filters","free_time":"Free time","free_time_on":"Free time on","fri":"fri","friday":"friday","hall":"hall","halls":"halls","home":"home","jan":"jan","january":"january","jul":"jul","july":"july","jun":"jun","june":"june","list":"list","mar":"mar","march":"march","may":"may","mon":"mon","monday":"monday","month":"month","more":"more","new_event":"new event","no":"no","not_approved":"not approved","nov":"nov","november":"november","oct":"oct","october":"october","ok":"ok","only_free_slots":"Only free slots","pick":"pick","pick_a_time_for_event":"Pick a time for event","pick_all_items_and_then_pick_a_time_for_event":"Pick all items and then pick a time for event","pick_time":"Pick time","please_select_all_filters":"Please select all filters","reset":"reset","sat":"sat","saturday":"saturday","search":"search","sep":"sept","sept":"sept","september":"september","settings":"settings","sun":"sun","sunday":"sunday","suspended_halls":"suspended","suspended_workers":"suspended","temp":"temp","template":"template","templates":"templates","thu":"thu","thursday":"thursday","time":"time","title":"title","to_date":"to date","today":"today","total":"total","tue":"tue","tuesday":"tuesday","view":"view","wed":"wed","wednesday":"wednesday","week":"week","with_all_events":"With all events","with_events_per_client":"With events per client","worker":"worker","workers":"workers","yes":"yes"},"en.validation":{"accepted":"The :attribute must be accepted.","active_url":"The :attribute is not a valid URL.","after":"The :attribute must be a date after :date.","after_or_equal":"The :attribute must be a date after or equal to :date.","alpha":"The :attribute must only contain letters.","alpha_dash":"The :attribute must only contain letters, numbers, dashes and underscores.","alpha_num":"The :attribute must only contain letters and numbers.","array":"The :attribute must be an array.","attributes":[],"before":"The :attribute must be a date before :date.","before_or_equal":"The :attribute must be a date before or equal to :date.","between":{"array":"The :attribute must have between :min and :max items.","file":"The :attribute must be between :min and :max kilobytes.","numeric":"The :attribute must be between :min and :max.","string":"The :attribute must be between :min and :max characters."},"boolean":"The :attribute field must be true or false.","confirmed":"The :attribute confirmation does not match.","custom":{"attribute-name":{"rule-name":"custom-message"}},"date":"The :attribute is not a valid date.","date_equals":"The :attribute must be a date equal to :date.","date_format":"The :attribute does not match the format :format.","different":"The :attribute and :other must be different.","digits":"The :attribute must be :digits digits.","digits_between":"The :attribute must be between :min and :max digits.","dimensions":"The :attribute has invalid image dimensions.","distinct":"The :attribute field has a duplicate value.","email":"The :attribute must be a valid email address.","ends_with":"The :attribute must end with one of the following: :values.","exists":"The selected :attribute is invalid.","file":"The :attribute must be a file.","filled":"The :attribute field must have a value.","gt":{"array":"The :attribute must have more than :value items.","file":"The :attribute must be greater than :value kilobytes.","numeric":"The :attribute must be greater than :value.","string":"The :attribute must be greater than :value characters."},"gte":{"array":"The :attribute must have :value items or more.","file":"The :attribute must be greater than or equal :value kilobytes.","numeric":"The :attribute must be greater than or equal :value.","string":"The :attribute must be greater than or equal :value characters."},"image":"The :attribute must be an image.","in":"The selected :attribute is invalid.","in_array":"The :attribute field does not exist in :other.","integer":"The :attribute must be an integer.","ip":"The :attribute must be a valid IP address.","ipv4":"The :attribute must be a valid IPv4 address.","ipv6":"The :attribute must be a valid IPv6 address.","json":"The :attribute must be a valid JSON string.","lt":{"array":"The :attribute must have less than :value items.","file":"The :attribute must be less than :value kilobytes.","numeric":"The :attribute must be less than :value.","string":"The :attribute must be less than :value characters."},"lte":{"array":"The :attribute must not have more than :value items.","file":"The :attribute must be less than or equal :value kilobytes.","numeric":"The :attribute must be less than or equal :value.","string":"The :attribute must be less than or equal :value characters."},"max":{"array":"The :attribute must not have more than :max items.","file":"The :attribute must not be greater than :max kilobytes.","numeric":"The :attribute must not be greater than :max.","string":"The :attribute must not be greater than :max characters."},"mimes":"The :attribute must be a file of type: :values.","mimetypes":"The :attribute must be a file of type: :values.","min":{"array":"The :attribute must have at least :min items.","file":"The :attribute must be at least :min kilobytes.","numeric":"The :attribute must be at least :min.","string":"The :attribute must be at least :min characters."},"multiple_of":"The :attribute must be a multiple of :value.","not_in":"The selected :attribute is invalid.","not_regex":"The :attribute format is invalid.","numeric":"The :attribute must be a number.","password":"The password is incorrect.","present":"The :attribute field must be present.","regex":"The :attribute format is invalid.","required":"The :attribute field is required.","required_if":"The :attribute field is required when :other is :value.","required_unless":"The :attribute field is required unless :other is in :values.","required_with":"The :attribute field is required when :values is present.","required_with_all":"The :attribute field is required when :values are present.","required_without":"The :attribute field is required when :values is not present.","required_without_all":"The :attribute field is required when none of :values are present.","same":"The :attribute and :other must match.","size":{"array":"The :attribute must contain :size items.","file":"The :attribute must be :size kilobytes.","numeric":"The :attribute must be :size.","string":"The :attribute must be :size characters."},"starts_with":"The :attribute must start with one of the following: :values.","string":"The :attribute must be a string.","timezone":"The :attribute must be a valid zone.","unique":"The :attribute has already been taken.","uploaded":"The :attribute failed to upload.","url":"The :attribute format is invalid.","uuid":"The :attribute must be a valid UUID."}});
})();
