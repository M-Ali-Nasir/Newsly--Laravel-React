import React, { useState } from "react";

const NewsNav = ({ onSearch, preSearch }) => {
    const [searchTerm, setSearchTerm] = useState(preSearch);
    const [category, setCategory] = useState("all");
    const [fromDate, setFromDate] = useState("");
    const [toDate, setToDate] = useState("");

    const handleSearch = (e) => {
        e.preventDefault();
        // Trigger the search with the selected filters
        onSearch({
            search: searchTerm,
        });
    };

    return (
        <nav className="navbar justify-end">
            <form className="d-flex" onSubmit={handleSearch} method="get">
                <input
                    className="form-control me-2 border-0 rounded-1"
                    type="search"
                    placeholder="Search"
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                />

                <button
                    className="btn btn-outline-success border-0 rounded-1"
                    type="submit"
                >
                    Search
                </button>
            </form>
        </nav>
    );
};

export default NewsNav;
