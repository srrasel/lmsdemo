import useUtility from '@/app/_hooks/useUtility'
import React, { useState } from 'react'
import Link from 'next/link';

export default function BookSyllabus({ book }) {
    const { trans } = useUtility();
    const [accordionShow, setAccordionShow] = useState(null);

    const toggleAccordion = (index) => {
        if (accordionShow === index) {
            setAccordionShow(0);
        } else {
            setAccordionShow(index);
        }
    }

    return (
        <div className="syllabus-wrapper mt-5">
            <div className="syllabus-header mb-4">
                <div className="syllabus-header__wrapper">
                    <div className="syllabus-header__icon">
                        <h2><i className="las la-book-open"></i></h2>
                    </div>
                    <div className="syllabus-header__content">
                        <h5 className="syllabus-header__title">{trans(`Chapters`)}</h5>
                        <ul className="syllabus-list">
                            <li className="syllabus-list__item">{book?.chapters?.length ?? 0} {trans(`Chapters`)}</li>
                            <li className="syllabus-list__item">
                                {book?.chapters?.reduce((total, chapter) => total + (chapter?.lessons?.length || 0), 0)} {trans(`Lessons`)}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div className="accordion module--accordion">
                {book?.chapters?.map((chapter, index) => (
                    <div className="accordion-item" key={index}>
                        <h2 className="accordion-header">
                            <button onClick={() => toggleAccordion(index + 1)} className="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target={`#chapter-${index}`} aria-expanded={accordionShow == index + 1}>
                                <span className="collapse-icon">
                                    {
                                        accordionShow == index + 1 ?
                                            <i className="las la-caret-up"></i> :
                                            <i className="las la-caret-down"></i>
                                    }
                                    {trans(chapter?.title)}
                                </span>
                                <span className="module-timeline">
                                    {chapter?.lessons?.length || 0} {trans(`Lessons`)}
                                </span>
                            </button>
                        </h2>
                        <div className='accordion-body p-0'>
                            <div id={`chapter-${index}`} className={`accordion-collapse collapse ${accordionShow == index + 1 && 'show'}`} >
                                {chapter?.lessons?.map((lesson, lessonIndex) => (
                                    <div className="module-overview" key={lessonIndex}>
                                        <ul className="module-overview__list">
                                            <li className="module-overview__item">
                                                <div className="left">
                                                    <span className="icon">
                                                        <i className="las la-file-alt"></i>
                                                    </span>
                                                    <Link href={`/library/${book?.id}/${lesson?.slug}`} className="module-overview__title-link">
                                                        {lesson?.title}
                                                    </Link>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    )
}
