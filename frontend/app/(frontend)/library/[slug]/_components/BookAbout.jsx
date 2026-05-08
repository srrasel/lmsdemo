'use client'
import useUtility from '@/app/_hooks/useUtility';
import React from 'react'

export default function BookAbout({ book }) {
    const { trans } = useUtility();

    return (
        <div className="about-courses">
            <div className="course-require__wrapper d-block">
                <div className="row gy-4 align-items-center">
                    <div className="col-md-12">
                        <div className="about-courses-content">
                            <h5 className="about-courses__title">{trans(`Description`)}</h5>
                            <p className="about-courses__desc " dangerouslySetInnerHTML={{ __html: book?.description }}></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}
