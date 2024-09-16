import React, { useState } from "react";
import NewsList from "@/Components/NewsList";
import NewsNav from "@/Components/NewsNav";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router, useForm } from "@inertiajs/react";

export default function Dashboard({
    all_articles,
    initialFilters,
    currentPage,
    totalPages,
    categories,
    preferences,
}) {
    var preSearch = initialFilters.search;
    if (preSearch == null) {
        preSearch = "";
    }
    var preFilter = initialFilters.categoryId;
    if (preFilter == null) {
        preFilter = "";
    }

    const [newsArticles, setNewsArticles] = useState(all_articles);

    const [searchTerm, setSearchTerm] = useState(preSearch);
    const [filteredCategory, setFilteredCategory] = useState(preFilter);

    const [page, setPagenumber] = useState(currentPage);
    // Handle search/filter updates
    const handleSearch = (filters) => {
        setSearchTerm(filters.search);
        filters = {
            search: filters.search,
            categoryId: filteredCategory,
            page: page,
        };
        router.get(route(route().current()), filters);
    };

    //Handle Filter
    const handleFilter = (e, category_id) => {
        e.preventDefault();
        setFilteredCategory(category_id);
        var filters = {
            search: searchTerm,
            page: 1,
            categoryId: category_id,
        };
        router.get(route("dashboard"), filters);
    };

    const paginate = (e, goto) => {
        e.preventDefault();

        // Prevent navigating to an invalid page
        if (goto < 1 || goto > totalPages) return;

        setPagenumber(goto); // Update the page number

        var filters = {
            search: searchTerm,
            categoryId: filteredCategory,
            page: goto, // Set the correct page number
        };

        router.get(route(route().current()), filters); // Make the request
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    {route().current() == "dashboard"
                        ? "All News"
                        : "Prefered News"}
                </h2>
            }
        >
            <Head
                title={
                    route().current() == "dashboard"
                        ? "All News"
                        : "Prefered News"
                }
            />

            <div className="container p-5">
                <NewsNav onSearch={handleSearch} preSearch={preSearch} />
                <div className="row">
                    {route().current() === "dashboard" ? (
                        <>
                            <div className="col-md-3">
                                <div className="bg-light my-4 py-4 px-2 h-100">
                                    <div className="flex">
                                        <h4 className="h4 fw-bold">Filters</h4>
                                        {categories.map(
                                            (category) =>
                                                filteredCategory ==
                                                    category.id && (
                                                    <div className="w-100">
                                                        <li
                                                            key={category.id} // Add key here
                                                            className="m-1 btn hover-bg-dark border border-1 px-2 py-1 text-nowrap float-end"
                                                            style={{
                                                                backgroundColor:
                                                                    "rgb(210,225,225)",
                                                                color: "#000",
                                                                border: "none",
                                                                padding:
                                                                    "10px 20px",
                                                                borderRadius:
                                                                    "5px",
                                                            }}
                                                        >
                                                            {category.name}{" "}
                                                            {/* Updated from filteredCategory to category.name */}
                                                        </li>
                                                    </div>
                                                )
                                        )}
                                    </div>
                                    <hr />
                                    <div className="mt-3 px-2">
                                        <ul className="d-flex row">
                                            {categories.map((category) => (
                                                <button
                                                    key={category.id}
                                                    className="col m-1 btn hover-bg-dark border border-1 px-2 py-1 text-nowrap"
                                                    style={{
                                                        backgroundColor:
                                                            "rgb(210,200,220)",
                                                        color: "#000",
                                                        border: "none",
                                                        padding: "10px 20px",
                                                        borderRadius: "5px",
                                                    }}
                                                    onClick={(e) =>
                                                        handleFilter(
                                                            e,
                                                            category.id
                                                        )
                                                    }
                                                >
                                                    {category.name}
                                                </button>
                                            ))}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div className="col-md-9">
                                <NewsList articles={all_articles} />
                            </div>
                        </>
                    ) : (
                        <div className="col-md-12">
                            <NewsList articles={all_articles} />
                        </div>
                    )}
                </div>

                <nav aria-label="Page navigation">
                    <ul className="pagination justify-center">
                        <li
                            className={
                                "page-item " +
                                (currentPage === 1 ? "disabled" : "")
                            }
                        >
                            <button
                                className="page-link"
                                onClick={(e) => paginate(e, currentPage - 1)}
                                aria-label="Previous"
                                disabled={currentPage === 1}
                            >
                                <span aria-hidden="true">&laquo;</span>
                            </button>
                        </li>

                        {Array.from({ length: totalPages }, (_, index) => (
                            <li
                                key={index}
                                className={`page-item ${
                                    currentPage === index + 1 ? " bg-dark" : ""
                                }`}
                            >
                                <button
                                    className={`page-link ${
                                        currentPage === index + 1
                                            ? " bg-dark"
                                            : ""
                                    }`}
                                    onClick={(e) => paginate(e, index + 1)}
                                >
                                    {index + 1}
                                </button>
                            </li>
                        ))}

                        <li
                            className={
                                "page-item " +
                                (currentPage == totalPages ? "disabled" : "")
                            }
                        >
                            <button
                                className="page-link"
                                onClick={(e) => paginate(e, currentPage + 1)}
                                aria-label="Next"
                                disabled={currentPage === totalPages}
                            >
                                <span aria-hidden="true">&raquo;</span>
                            </button>
                        </li>
                    </ul>
                </nav>
            </div>
        </AuthenticatedLayout>
    );
}
