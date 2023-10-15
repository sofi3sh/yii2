import React from 'react';

const pagesCount = ({ totalCount, pageSize }) =>
  Math.ceil(totalCount / pageSize);

const pagesRange = ({ totalCount, pageSize }) =>
  Array.from(
    { length: pagesCount({ totalCount, pageSize }) },
    (value, key) => key
  );

const pagesHref = pageNumber => {
  const urlParams = new URLSearchParams(window.location.search);
  const currentUrl = new URL(window.location.href);

  urlParams.set('page', pageNumber);
  currentUrl.search = urlParams.toString();
  return currentUrl.toString();
};

const firstPage = currentPage => {
  if (currentPage > 0) {
    return <a href={pagesHref(1)}>{'<<'}</a>;
  }

  return <span>{'<<'}</span>;
};

const lastPage = (currentPage, pagesCount) => {
  if (pagesCount !== currentPage + 1) {
    return <a href={pagesHref(pagesCount)}>{'>>'}</a>;
  }

  return <span>{'>>'}</span>;
};

const getActivePageClassName = (pageNumber, currentPage) =>
  pageNumber === currentPage ? 'active' : '';

const Pagination = ({ currentPage, basicInfo, url }) => {
  if (!basicInfo) {
    return false;
  }
  const { totalCount, defaultPageSize: pageSize } = basicInfo;

  return (
    <ul className="pagination">
      <li className="prev">{firstPage(currentPage)}</li>
      {pagesRange({ totalCount, pageSize }).map(pageNumber => {
        return (
          <li
            className={getActivePageClassName(pageNumber, currentPage)}
            key={pageNumber}
          >
            <a href={pagesHref(pageNumber + 1)}>{pageNumber + 1}</a>
          </li>
        );
      })}
      <li className="next">
        {lastPage(currentPage, pagesCount({ totalCount, pageSize }))}
      </li>
    </ul>
  );
};

export default Pagination;
