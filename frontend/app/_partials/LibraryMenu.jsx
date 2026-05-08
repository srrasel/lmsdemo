"use client";

import { useState, useEffect } from "react";
import { Image } from "react-bootstrap";
import useUtility from "../_hooks/useUtility";
import Link from "next/link";
import ENDPOINTS from "@/lib/endpoints";
import { bookImage, request } from "@/lib/helpers";

export default function LibraryMenu({ handleClose }) {
    const [isOpen, setIsOpen] = useState(false);
    const [isMobile, setIsMobile] = useState(false);
    const [books, setBooks] = useState([]);
    const { trans } = useUtility();

    // Detect screen width and update `isMobile`
    useEffect(() => {
        const checkScreenSize = () => {
            const mobileView = window.innerWidth < 1200;
            setIsMobile(mobileView);
            if (!mobileView) {
                setIsOpen(false); // Ensure menu is closed when switching to desktop
            }
        };

        checkScreenSize(); // Initial check
        window.addEventListener("resize", checkScreenSize);

        return () => window.removeEventListener("resize", checkScreenSize);
    }, []);

    // Fetch books
    useEffect(() => {
        const fetchBooks = async () => {
            try {
                const response = await request.get(ENDPOINTS.LIBRARY_BOOKS);
                if (response.data?.data?.books?.data) {
                    setBooks(response.data.data.books.data.slice(0, 9));
                }
            } catch (error) {
                console.error("Error fetching library books:", error);
            }
        };

        fetchBooks();
    }, []);

    // Toggle menu only when on mobile
    const toggleMenu = (e) => {
        if (isMobile) {
            e.preventDefault();
            setIsOpen((prev) => !prev);
        }
    };

    return (
        <li className="nav-item dropdown">
            <Link
                className={`nav-link ${isMobile && isOpen ? "show" : ""}`}
                href="/library"
                role="button"
                data-bs-toggle={isMobile ? "dropdown" : ""}
                aria-expanded={isMobile && isOpen ? "true" : "false"}
                onClick={toggleMenu}
            >
                {trans(`Library`)}
                <span className="nav-item__icon">
                    <i className="las la-angle-down"></i>
                </span>
            </Link>
            <div className={`dropdown-menu mega-menu ${isMobile && isOpen ? "show" : ""}`}>
                <div className="container">
                    <div className="mega-menu-content">
                        <div className="mega-menu-left">
                            <h6 className="mega-menu-title">{trans(`Top Books`)}</h6>
                            <div className="mega-menu-wrapper">
                                {books.map((book, index) => (
                                    <Link href={`/library/${book?.id}`} onClick={handleClose} key={index} className="mega-menu-item">
                                        <div className="mega-menu-item-image">
                                            <Image src={bookImage(book?.image)} alt={trans(book?.title)} className="fit-image" />
                                        </div>
                                        <div className="mega-menu-item-content">
                                            <h6 className="title">{trans(book?.title)}</h6>
                                            <p className="desc">{trans(book?.category?.name)}</p>
                                        </div>
                                    </Link>
                                ))}
                            </div>
                            <div className="mt-4 text-center">
                                <Link href="/library" onClick={handleClose} className="btn btn--base">
                                    {trans(`View All Books`)}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    );
}
