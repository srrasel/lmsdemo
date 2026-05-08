"use client";

import useUtility from '@/app/_hooks/useUtility';
import Pagination from '@/app/_partials/Pagination';
import ENDPOINTS from '@/lib/endpoints';
import { bookImage, request, showDateTime } from '@/lib/helpers';

import Link from 'next/link';
import { useEffect, useState } from 'react';
import { Image } from 'react-bootstrap';
import BookSkeleton from './BookSkeleton';
import usePaginationParams from '@/app/_hooks/usePaginationParams';


export default function LibraryBooks() {

    const { trans } = useUtility();
    const { pageNumber } = usePaginationParams();

    const [loading, setLoading] = useState(false);
    const [books, setBooks] = useState([]);

    useEffect(() => {
        async function fetchBooks() {
            setLoading(true)
            try {
                const data = await request.get(`${ENDPOINTS.LIBRARY_BOOKS}?page=${pageNumber}`);
                setBooks(data.data.data.books);
                setLoading(false)

            } catch (error) {
                setLoading(false)
                console.error("Error fetching books:", error)
            }
        }
        fetchBooks()
    }, [pageNumber])

    return (
        <>
            <section className="library py-50">
                <div className="container">
                    <div className="slider-top">
                        <div className="section-heading style-left section-heading-dark">
                            <h2 className="section-heading__title mb-0">{trans('Library')}</h2>
                        </div>
                    </div>
                    <div className="row gy-4">
                        {
                            loading ?
                                <BookSkeleton count={8} /> :
                                books.data?.length > 0 ? (
                                    books.data?.map((book, index) => (
                                        <div className="col-xl-3 col-lg-4 col-sm-6" key={index}>
                                            <div className="blog-item" >
                                                <div className="blog-item__thumb">
                                                    <Link href={`/library/${book?.id}`} className="blog-item__thumb-link">
                                                        <Image src={bookImage(book?.image)} className="fit-image" alt="book_image" />
                                                    </Link>
                                                </div>
                                                <div className="blog-item__content">
                                                    <div className="blog-item__date">
                                                        <span className="icon"><i className="far fa-clock"></i></span>
                                                        <span className="text">{showDateTime(book?.created_at, 'DD MMM YYYY')}</span>
                                                    </div>
                                                    <h6 className="blog-item__title">
                                                        <Link href={`/library/${book?.id}`} className="blog-item__title-link">{trans(book?.title)}</Link>
                                                    </h6>
                                                    <p className="blog-item__desc">
                                                        {trans(book?.category?.name)}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    ))
                                ) : (
                                    <div className="col-12 text-center">
                                        <p>{trans('No books found')}</p>
                                    </div>
                                )
                        }
                        {
                            books.data?.length > 0 && (
                                <div className="Page navigation example mt-3">
                                    <Pagination loading={loading} pagination={books} />
                                </div>
                            )
                        }
                    </div>
                </div>

            </section>
        </>
    );
}
