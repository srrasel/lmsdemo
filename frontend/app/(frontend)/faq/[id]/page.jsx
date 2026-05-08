import React from 'react';
import ENDPOINTS from "@/lib/endpoints";
import { getApiBaseUrl, getMetaTitle, frontendImage } from "@/lib/helpers";
import { Image } from 'react-bootstrap';
import Link from 'next/link';

export async function generateMetadata({ params }) {
    const { id } = params;
    // Note: We might want to fetch the specific FAQ here for dynamic metadata,
    // but since we only have the list endpoint, we'll use a generic title or fetch list.
    return getMetaTitle(`FAQ Details`);
}

async function getFaqs() {
    try {
        const res = await fetch(`${getApiBaseUrl()}${ENDPOINTS.FAQS}`, {
            cache: 'no-store'
        });

        if (!res.ok) {
            throw new Error('Failed to fetch FAQs');
        }

        return res.json();
    } catch (error) {
        console.error('Error fetching FAQs:', error);
        return { data: { faqs: [] } };
    }
}

export default async function FaqDetails({ params }) {
    const { id } = params;
    const data = await getFaqs();
    const faqs = data?.data?.faqs || [];
    
    const faq = faqs.find(f => f.id == id);

    if (!faq) {
         return (
            <section className="py-50">
                <div className="container">
                    <div className="text-center">
                        <h3>FAQ Not Found</h3>
                        <Link href="/faq" className="btn btn--base mt-3">Back to FAQs</Link>
                    </div>
                </div>
            </section>
        );
    }

    return (
        <section className="py-50">
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-lg-10">
                        <div className="mb-4">
                            <Link href="/faq" className="text-decoration-none text-muted">
                                <i className="fas fa-arrow-left me-2"></i> Back to FAQs
                            </Link>
                        </div>
                        
                        <div className="card custom--card border-0">
                            <div className="card-body p-4 p-lg-5">
                                <h2 className="mb-4">{faq.question}</h2>
                                
                                {faq.image && (
                                    <div className="mb-4">
                                        <Image 
                                            src={frontendImage(faq.image, 'faq')} 
                                            className="img-fluid rounded w-100" 
                                            style={{ maxHeight: '400px', objectFit: 'cover' }}
                                            alt={faq.question} 
                                        />
                                    </div>
                                )}

                                <div className="faq-content">
                                    <p dangerouslySetInnerHTML={{ __html: faq.answer }}></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
