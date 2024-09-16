import React, { useState } from "react";
import NewsList from "@/Components/NewsList";
import NewsNav from "@/Components/NewsNav";
import { Link, Head, router } from "@inertiajs/react";

export default function Welcome({
    auth,
    all_articles,
    initialFilters,
    currentPage,
    totalPages,
}) {
    const currentYear = new Date().getFullYear();
    console.log(all_articles);
    console.log(currentPage);

    var preSearch = initialFilters.search;
    if (preSearch == null) {
        preSearch = "";
    }

    const [newsArticles, setNewsArticles] = useState(all_articles);
    const [searchTerm, setSearchTerm] = useState(preSearch);
    const [page, setPagenumber] = useState(currentPage);
    // Handle search/filter updates
    const handleSearch = (e, filters) => {
        e.preventDefault();
        // Trigger the search with the selected filters
        filters = {
            search: searchTerm,
        };
        console.log("run");
        router.get(route(route().current()), filters);
    };

    const paginate = (e, goto) => {
        e.preventDefault();

        // Prevent navigating to an invalid page
        if (goto < 1 || goto > totalPages) return;

        setPagenumber(goto); // Update the page number

        var filters = {
            search: searchTerm,
            page: goto, // Set the correct page number
        };

        router.get(route(route().current()), filters); // Make the request
    };

    return (
        <>
            <Head title="Welcome" />
            <div className="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
                <img
                    id="background"
                    className="absolute -left-20 top-0 max-w-[877px]"
                    src="https://laravel.com/assets/img/welcome/background.svg"
                />
                <div className="relative min-h-screen flex flex-col items-center selection:bg-[#FF2D20] selection:text-white">
                    <div className="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                        <nav className="-mx-3 flex flex-1 p-3">
                            <span className="w-100">
                                <Link
                                    href="/"
                                    className="rounded-md h3 px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white "
                                >
                                    Newsly
                                </Link>
                                <Link
                                    href={route("guestNews.top")}
                                    className={
                                        "rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white " +
                                        (route().current() == "guestNews.top"
                                            ? "border border-start-0 border-top-0 border-end-0"
                                            : " ")
                                    }
                                >
                                    Top News
                                </Link>
                                <Link
                                    href={route("guestNews.sports")}
                                    className={
                                        "rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white " +
                                        (route().current() == "guestNews.sports"
                                            ? "border border-start-0 border-top-0 border-end-0"
                                            : " ")
                                    }
                                >
                                    Sports
                                </Link>
                                <Link
                                    href={route("guestNews.entertainment")}
                                    className={
                                        "rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white " +
                                        (route().current() ==
                                        "guestNews.entertainment"
                                            ? "border border-start-0 border-top-0 border-end-0"
                                            : " ")
                                    }
                                >
                                    Entertainment
                                </Link>
                            </span>
                            <span className="w-100 flex justify-end">
                                {auth.user ? (
                                    <Link
                                        href={route("dashboard")}
                                        className="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                    >
                                        Dashboard
                                    </Link>
                                ) : (
                                    <>
                                        <Link
                                            href={route("login")}
                                            className="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                        >
                                            Log in
                                        </Link>
                                        <Link
                                            href={route("register")}
                                            className="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                        >
                                            Register
                                        </Link>
                                    </>
                                )}
                            </span>
                        </nav>

                        <main className="mt-6 text-dark">
                            <div className="container p-5">
                                <nav className="navbar justify-end">
                                    <form
                                        className="d-flex"
                                        onSubmit={handleSearch}
                                        method="get"
                                    >
                                        <input
                                            className="form-control me-2 border-0 rounded-1"
                                            type="search"
                                            placeholder="Search"
                                            value={searchTerm}
                                            onChange={(e) =>
                                                setSearchTerm(e.target.value)
                                            }
                                        />

                                        <button
                                            className="btn btn-outline-success border-0 rounded-1"
                                            type="submit"
                                        >
                                            Search
                                        </button>
                                    </form>
                                </nav>
                                <NewsList articles={all_articles} />
                                <nav aria-label="Page navigation">
                                    <ul className="pagination justify-center">
                                        <li
                                            className={
                                                "page-item " +
                                                (currentPage === 1
                                                    ? "disabled"
                                                    : "")
                                            }
                                        >
                                            <button
                                                className="page-link"
                                                onClick={(e) =>
                                                    paginate(e, currentPage - 1)
                                                }
                                                aria-label="Previous"
                                                disabled={currentPage === 1}
                                            >
                                                <span aria-hidden="true">
                                                    &laquo;
                                                </span>
                                            </button>
                                        </li>

                                        {Array.from(
                                            { length: totalPages },
                                            (_, index) => (
                                                <li
                                                    key={index}
                                                    className={`page-item ${
                                                        currentPage ===
                                                        index + 1
                                                            ? " bg-dark"
                                                            : ""
                                                    }`}
                                                >
                                                    <button
                                                        className={`page-link ${
                                                            currentPage ===
                                                            index + 1
                                                                ? " bg-dark"
                                                                : ""
                                                        }`}
                                                        onClick={(e) =>
                                                            paginate(
                                                                e,
                                                                index + 1
                                                            )
                                                        }
                                                    >
                                                        {index + 1}
                                                    </button>
                                                </li>
                                            )
                                        )}

                                        <li
                                            className={
                                                "page-item " +
                                                (currentPage == totalPages
                                                    ? "disabled"
                                                    : "")
                                            }
                                        >
                                            <button
                                                className="page-link"
                                                onClick={(e) =>
                                                    paginate(e, currentPage + 1)
                                                }
                                                aria-label="Next"
                                                disabled={
                                                    currentPage === totalPages
                                                }
                                            >
                                                <span aria-hidden="true">
                                                    &raquo;
                                                </span>
                                            </button>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </main>

                        <footer className="py-16 text-center text-sm text-black dark:text-white/70">
                            Newsly &copy; {currentYear}
                        </footer>
                    </div>
                </div>
            </div>
        </>
    );
}
