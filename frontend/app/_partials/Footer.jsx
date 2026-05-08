'use client';
import React, { useEffect, useState } from 'react';

import useUtility from '@/app/_hooks/useUtility';
import { Logo } from './_navbar/Logo';
import { useSection } from './_sections/_hooks/useSection';
import { Form, Formik } from 'formik';
import SubmitBtn from './SubmitBtn';
import Subscription from './Subscription';
import FormField from '../_forms/FormField';
import { useSelector } from 'react-redux';
import usePolicyPages from './_hooks/usePolicyPages';
import Link from 'next/link';
import ENDPOINTS from '@/lib/endpoints';
import { request, strLimit } from '@/lib/helpers';

export const Footer = () => {
    const { content, elements } = useSection('footer');
    const { policyPages } = usePolicyPages();

    const { trans } = useUtility();
    const { initialValues, validationSchema, handleSubmit } = Subscription();
    const { data: categories } = useSelector((state) => state?.categories);
    const { data: instructorData } = useSelector((state) => state?.instructor);

    const [footerMenus, setFooterMenus] = useState({});

    useEffect(() => {
        async function fetchFooterMenus() {
            try {
                const response = await request.get(ENDPOINTS.FOOTER_MENUS);
                const grouped = response?.data?.data?.footer_menus || {};
                setFooterMenus(grouped);
            } catch (error) {
                console.error("Error fetching footer menus:", error);
            }
        }

        fetchFooterMenus();
    }, []);

    return (
        <footer className="footer-area  py-50">
            <div className="newsletter">
                <div className="container">
                    <div className="newsletter-wrapper mb-50 pb-50">
                        <div className="row gy-4">
                            <div className="col-md-6">
                                <div className="newsletter__content">
                                    <h4 className="newsletter__heading">
                                        {trans(content?.data_values.news_letter_heading)}
                                    </h4>
                                    <p className="newsletter__text">
                                        {trans(content?.data_values.news_letter_subheading)}
                                    </p>
                                </div>
                            </div>
                            <div className="col-md-6">
                                <Formik
                                    initialValues={initialValues}
                                    validationSchema={validationSchema}
                                    onSubmit={handleSubmit}
                                >
                                    {({ isSubmitting }) => (
                                        <Form className="newsletter-form">
                                            <div className="newsletter-form__wrapper">
                                                <div className="newsletter-form__input">
                                                    <FormField
                                                        name="email"
                                                        fromGroup={false}
                                                        required={true}
                                                        className="form-control form--control"
                                                        placeholder="Enter Your Email"
                                                    />
                                                </div>
                                                <div className="newsletter-form__button">
                                                    <SubmitBtn
                                                        type="submit"
                                                        className="btn btn--white-shadow btn--base"
                                                        isSubmitting={isSubmitting}
                                                        title={trans('Subscribe')}
                                                    />
                                                </div>
                                            </div>
                                        </Form>
                                    )}
                                </Formik>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div className="footer-area">
                <div className="container">
                    <div className="footer-item-wrapper">
                        <div className="footer-item">
                            <div className="footer-item__logo">
                                <Logo />
                            </div>

                            <p className="footer-item__desc">
                                {trans(content?.data_values.footer_description)}
                            </p>

                            <div className="social">
                                <h5 className="footer-item__title">{trans(`Follow Us on`)}</h5>
                                <ul className="social-list">
                                    {elements?.map((item, index) => (
                                        <li key={index} className="social-list__item">
                                            <Link
                                                target="_blank"
                                                href={`${item?.data_values?.url}`}
                                                className="social-list__link flex-center"
                                                dangerouslySetInnerHTML={{
                                                    __html: item?.data_values?.social_icon,
                                                }}
                                            ></Link>
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        </div>

                        {/* Dynamic Footer Menus */}
                        {Object.entries(footerMenus).map(([sectionName, items], index) => (
                            <div className="footer-item" key={sectionName || index}>
                                <h5 className="footer-item__title">{trans(sectionName)}</h5>
                                <ul className="footer-menu">
                                    {items?.map((menu, i) => (
                                        <li key={menu.id || i} className="footer-menu__item">
                                            <Link
                                                href={menu.url || "/"}
                                                className="footer-menu__link"
                                            >
                                                {trans(menu.name)}
                                            </Link>
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </footer>
    );
};

export default Footer;
