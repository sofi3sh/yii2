import React from 'react';
import withData from '../hocs/withData';

export const manageTranslation = (phrase, translations) => {
  const phrasePlaceholders = phrase.match(/\{([^}]*)}/gm);
  const phraseInitialPattern = phrase.replace(
    /{[\w\d\s\u0400-\u04FF.,-]+}/g,
    '{value}'
  );

  if (!phrasePlaceholders || !translations[phraseInitialPattern]) {
    const phraseWithoutPlaceholades = phrase.replace(/({|})/g, '');
    return translations[phraseWithoutPlaceholades] || phraseWithoutPlaceholades;
  }

  const translationWithoutPlaceholades = translations[
    phraseInitialPattern
  ].split('{value}');
  let result = '';

  translationWithoutPlaceholades.forEach((translation, index) => {
    result += translation;
    if (phrasePlaceholders[index]) {
      result += phrasePlaceholders[index].replace(/({|})/g, '');
    }
  });

  return result;
};

const Translate = ({ children: phrase, translations }) => {
  return (
    <React.Fragment>{manageTranslation(phrase, translations)}</React.Fragment>
  );
};

export default withData([])(Translate);
