import React, { useState } from "react";

const TagInput = () => {
    const [tags, setTags] = useState([]);
    const [input, setInput] = useState("");

    const handleInputChange = (e) => {
        setInput(e.target.value);
    };

    const handleInputKeyDown = (e) => {
        if (e.key === "Enter" && input) {
            e.preventDefault();
            if (!tags.includes(input.trim())) {
                setTags([...tags, input.trim()]);
                setInput("");
            }
        }
    };

    const removeTag = (index) => {
        setTags(tags.filter((_, i) => i !== index));
    };

    return (
        <div className="w-full max-w-md">
            <div className="flex flex-wrap gap-2 p-2 border border-0">
                {tags.map((tag, index) => (
                    <span
                        key={index}
                        className="flex items-center bg-blue-100 text-blue-800 text-sm font-medium px-2 py-1 rounded"
                    >
                        {tag}
                        <button
                            onClick={() => removeTag(index)}
                            className="ml-1 text-blue-600 hover:text-blue-800"
                        >
                            &#x2715;
                        </button>
                    </span>
                ))}
                <input
                    type="text"
                    value={input}
                    onChange={handleInputChange}
                    onKeyDown={handleInputKeyDown}
                    className="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    placeholder="Type and press Enter to add tags"
                />
            </div>
        </div>
    );
};

export default TagInput;
