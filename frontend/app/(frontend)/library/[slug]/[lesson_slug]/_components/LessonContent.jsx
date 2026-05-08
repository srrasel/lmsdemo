"use client";

import useUtility from '@/app/_hooks/useUtility';
import Link from 'next/link';
import React, { useMemo } from 'react';

export default function LessonContent({ book, lesson }) {
    const { trans } = useUtility();

    // Flatten all lessons to find prev/next
    const allLessons = useMemo(() => {
        if (!book?.chapters) return [];
        return book.chapters.reduce((acc, chapter) => {
            if (chapter.lessons) {
                return [...acc, ...chapter.lessons];
            }
            return acc;
        }, []);
    }, [book]);

    const currentIndex = allLessons.findIndex(l => l.slug === lesson.slug);
    const prevLesson = currentIndex > 0 ? allLessons[currentIndex - 1] : null;
    const nextLesson = currentIndex < allLessons.length - 1 ? allLessons[currentIndex + 1] : null;

    return (
        <section className="lesson-content py-50">
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-lg-10">
                        <div className="mb-4">
                             <Link href={`/library/${book.id}`} className="btn btn-outline--base btn-sm">
                                <i className="las la-arrow-left"></i> {trans('Back to Book Details')}
                             </Link>
                        </div>
                        
                        <div className="card custom--card">
                            <div className="card-body p-4 p-lg-5">
                                <div className="section-heading style-left mb-4 border-bottom pb-3">
                                    <h2 className="section-heading__title mb-2" style={{color:'#000'}}>{trans(lesson?.title)}</h2>
                                    <p className="section-heading__desc fs-16 text-muted">{trans(book?.title)}</p>
                                </div>
                                
                                <div className="lesson-body mb-5 lesson-content-text">
                                     <div dangerouslySetInnerHTML={{ __html: lesson?.content }}></div>
                                </div>

                                <div className="lesson-navigation d-flex justify-content-between align-items-center pt-4 border-top">
                                    {prevLesson ? (
                                        <Link href={`/library/${book.id}/${prevLesson.slug}`} className="btn btn--base">
                                            <i className="las la-arrow-left"></i> {trans('Previous Lesson')}
                                        </Link>
                                    ) : (
                                        <div></div> 
                                    )}

                                    {nextLesson ? (
                                        <Link href={`/library/${book.id}/${nextLesson.slug}`} className="btn btn--base">
                                            {trans('Next Lesson')} <i className="las la-arrow-right"></i>
                                        </Link>
                                    ) : (
                                        <div></div>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
