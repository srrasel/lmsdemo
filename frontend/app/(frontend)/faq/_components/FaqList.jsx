'use client';

import React from 'react';
import useUtility from "@/app/_hooks/useUtility";
import Link from 'next/link';
import { frontendImage } from '@/lib/helpers';
import { Image } from 'react-bootstrap';

export default function FaqList({ faqs }) {
    const { trans } = useUtility();

    if (!faqs || faqs.length === 0) {
        return (
            <div className="text-center py-5">
                <h3>{trans('No FAQs found')}</h3>
            </div>
        );
    }

    return (
        <div className="row g-4">
            {faqs.map((faq, index) => (
                <div className="col-lg-6" key={faq.id}>
                    <div className="faq-item d-flex align-items-start gap-3 p-3 border rounded h-100">
                        <div className="faq-item__thumb flex-shrink-0" style={{ width: '100px', height: '100px' }}>
                             <Image 
                                src={frontendImage(faq?.image, 'faq')} 
                                className="w-100 h-100 object-fit-cover rounded" 
                                alt="faq_image" 
                                onError={(e) => {
                                    e.target.src = 'https://placehold.co/100x100?text=FAQ';
                                }}
                            />
                        </div>
                        <div className="faq-item__content">
                            <h5 className="faq-item__title mb-2">
                                <Link href={`/faq/${faq.id}`} className="text-decoration-none text-dark">
                                    {trans(faq.question)}
                                </Link>
                            </h5>
                            <p className="faq-item__desc text-muted mb-0 small">
                                {trans(faq.answer)?.substring(0, 100)}...
                            </p>
                        </div>
                    </div>
                </div>
            ))}
        </div>
    );
}

