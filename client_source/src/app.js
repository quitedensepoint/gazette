'use strict';

import _ from 'lodash';
import styles from '../assets/css/gazette.scss';
import L from 'leaflet';
import jquery from 'jquery';
import textFit from 'textFit';

window.$ = window.jQuery = jquery;

const fitHeader = () => {
    textFit(jquery('.textFit h1'), { maxFontSize: 1000, widthOnly: true });
};

const formatAMPM = (date) => {
  let hours = date.getHours();
  const ampm = hours >= 12 ? 'pm' : 'am';
  hours = hours % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
  const strTime = hours + ' ' + ampm;
  return strTime;
};

jquery(document).ready(() => {
    fitHeader();

    window.addEventListener('resize', fitHeader);

    jquery('[data-datetime-currenthour]').text(formatAMPM(new Date()));
});
