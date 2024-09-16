import React from "react";
//import NewsItem from './NewsItem'; // Component for each news article

const NewsList = ({ articles }) => {
    return (
        <div className="mt-4">
            <div className="rounded-2">
                {articles.length > 0 ? (
                    articles.map(
                        (article, index) => (
                            //article.author != null && ( // Use logical AND to render conditionally
                            <li
                                className="list-group-item border-0"
                                key={index}
                            >
                                <h3 className="h3" key={index}>
                                    {" "}
                                    {article.title}{" "}
                                </h3>
                                <p>
                                    <span className="fw-bold">
                                        Author:&nbsp;
                                    </span>
                                    {article.author}&nbsp;&nbsp;&nbsp;
                                    <span className="fw-bold">
                                        Source:&nbsp;
                                    </span>
                                    {article.source}&nbsp;&nbsp;&nbsp;
                                    <span className="fw-bold">
                                        Published at:&nbsp;
                                    </span>
                                    {article.published_at}
                                </p>
                                <p className="mt-2">{article.description}</p>
                                <a href={article.url} target="_blank">
                                    Read More
                                </a>
                            </li>
                        )
                        //)
                    )
                ) : (
                    <p className="bg-warning rounded-1">
                        No news articles found
                    </p>
                )}
            </div>
        </div>
    );
};

export default NewsList;
