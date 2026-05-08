"use client";

import useUtility from '@/app/_hooks/useUtility';
import { bookImage, showDateTime } from '@/lib/helpers';
import React from 'react';
import { Image } from 'react-bootstrap';
import BookAbout from './BookAbout';
import BookSyllabus from './BookSyllabus';

export default function BookDetails({ book }) {

    const { trans } = useUtility();

    return (
        <>
            <section className="course-banner py-50 bg-img">
                <div className="container">
                    <div className="course-wrapper">
                        <div className="row gy-4 align-items-center justify-content-between flex-lg-row-reverse">
                            <div className="col-xl-4 col-lg-5">
                                <div className="course-thumb">
                                    <Image className="fit-image" src={bookImage(book?.image)} alt="book_image" />
                                </div>
                            </div>
                            <div className="col-lg-7">
                                <span className="course-category">{trans(book?.category?.name)}</span>
                                <div className="course-content">
                                    <h2 className="course-content__title">{trans(book?.title)}</h2>
                                    <p className="course-content__desc">{trans(book?.category?.name)}</p>
                                </div>

                                <div className="course-info-wrapper">
                                    <div className="course-content-footer">
                                        <div className="course-banner__meta-item">
                                            <span className="icon"><i className="far fa-clock"></i></span>
                                            <span className="text text-white">{showDateTime(book?.created_at, 'DD MMM YYYY')}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section className="py-50">
                <div className="container">
                    <div className="row justify-content-center">
                         <div className="col-lg-12">
                            <BookAbout book={book} />
                            <BookSyllabus book={book} />
                         </div>
                    </div>
                </div>
            </section>
        </>
    );
}
