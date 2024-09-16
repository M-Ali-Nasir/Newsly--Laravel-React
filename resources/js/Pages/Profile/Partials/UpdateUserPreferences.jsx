import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import { Link, useForm, usePage, router } from "@inertiajs/react";
import { Transition } from "@headlessui/react";

export default function UpdateProfileInformation({
    categories,
    preferences,
    mustVerifyEmail,
    status,
    className = "",
}) {
    console.log(preferences);
    const user = usePage().props.auth.user;

    const handleAdd = (e, id) => {
        e.preventDefault();
        router.post(route("profile.addPreference", [id]));
    };
    const handleDelete = (e, id) => {
        // Replace this with your actual route logic, e.g., using React Router or Axios to send the request

        e.preventDefault();
        router.get(route("profile.deletePreference", [id]));
    };

    return (
        <section className={className + "w-100"}>
            <header>
                <h2 className="text-lg font-medium text-gray-900">
                    User Preferences
                </h2>
                <p className="mt-1 text-sm text-gray-600">
                    Update your account's news preferences.
                </p>
            </header>

            <form className="mt-6 space-y-6">
                <div>
                    <InputLabel htmlFor="topic" value="Topic" />
                    <p className="mt-1 text-sm text-gray-600">
                        Click to add to Preferences
                    </p>
                    <div className="mt-3">
                        <ul className="d-flex row">
                            {categories.map((category) => {
                                const preference = preferences.find(
                                    (pref) => pref.cat_id === category.id
                                );
                                return (
                                    <>
                                        <button
                                            key={category.id}
                                            className={
                                                "col m-1 btn hover-bg-dark border border-1 px-2 py-1 text-nowrap " +
                                                (preference
                                                    ? "disabled bg-white"
                                                    : "")
                                            }
                                            style={{
                                                backgroundColor:
                                                    "rgb(210,200,220)",
                                                color: "#000",
                                                border: "none",
                                                padding: "10px 20px",
                                                borderRadius: "5px",
                                            }}
                                            onClick={(e) =>
                                                handleAdd(e, category.id)
                                            }
                                        >
                                            {category.name}
                                        </button>
                                    </>
                                );
                            })}
                        </ul>
                    </div>
                </div>
            </form>
            <div className="mt-3">
                <h6 className="my-2">Saved Preferences:</h6>
                <ul className="flex row">
                    {preferences.map((topic) => {
                        // Find the category by cat_id to get the name
                        const category = categories.find(
                            (cat) => cat.id === topic.cat_id
                        );

                        return (
                            <div key={topic.id} className="flex col m-1">
                                <li className="list-group-item border border-0 me-0 pe-0 text-nowrap">
                                    {category
                                        ? category.name
                                        : "Unknown Category"}
                                </li>
                                <button
                                    className="h1 btn-sm ml-2 ms-0 me-2"
                                    onClick={(e) => handleDelete(e, topic.id)}
                                >
                                    <span>
                                        <i className="fw-bold fa-solid fa-xmark"></i>
                                    </span>
                                </button>
                            </div>
                        );
                    })}
                </ul>
            </div>
        </section>
    );
}
