import React from 'react';
import ENDPOINTS from "@/lib/endpoints";
import { getApiBaseUrl, getMetaTitle } from "@/lib/helpers";
import FaqList from './_components/FaqList';

export const metadata = getMetaTitle("FAQs");
export const dynamic = 'force-dynamic';

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

export default async function FaqPage() {
    const data = await getFaqs();
    const faqs = data?.data?.faqs || [];

    return (
        <section className="py-50">
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-lg-10">
                        <div className="section-heading text-center">
                            <h2 className="section-heading__title" style={{color:'#000'}}>Frequently Asked Questions</h2>
                            <p className="section-heading__desc" style={{color:'#000'}}>Find answers to common questions about our platform</p>
                        </div>
                        <FaqList faqs={faqs} />
                    </div>
                </div>
            </div>
        </section>
    );
}
