"use client";
import { useEffect, useState } from "react";
import { Form, Formik, ErrorMessage } from "formik";
import useUtility from "@/app/_hooks/useUtility";
import { FormField } from "@/app/_forms/FormsStore";
import Input from "@/app/_forms/Input";
import useDonation from "../_hooks/useDonation";
import Link from "next/link";
import PaymentGateway from "@/app/(user)/user/(deposit)/_components/PaymentGateway";
import usePaymentMethod from "@/app/(user)/user/(deposit)/_hooks/usePaymentMethod";

export default function DonationForm() {
    const { initialValues, validationSchema, handleSubmit, loading } = useDonation();
    const { trans } = useUtility();
    const { getPaymentMethods } = usePaymentMethod();
    const [paymentMethods, setPaymentMethods] = useState([]);
    const [imagePath, setImagePath] = useState("");
    const [gatewayLoading, setGatewayLoading] = useState(false);

    useEffect(() => {
        (async () => {
            setGatewayLoading(true);
            const { imagePath, paymentMethods } = await getPaymentMethods();
            setImagePath(imagePath);
            setPaymentMethods(paymentMethods);
            setGatewayLoading(false);
        })();
    }, [getPaymentMethods]);

    return (
        <div className="donation-wrapper">
            <Formik
                initialValues={initialValues}
                validationSchema={validationSchema}
                onSubmit={handleSubmit}
            >
                {({ setFieldValue }) => (
                    <Form className="donation-form">
                        <div className="card custom--card mb-4 border-0 shadow-sm">
                            <div className="card-body p-4">
                                <h4 className="mb-4">{trans("Make a Onetime Donation")}</h4>

                                <div className="row">
                                    <div className="col-md-6 mb-3">
                                        <label className="form-label">{trans("Full Name")}</label>
                                        <Input name="full_name" className="form-control" placeholder={trans("Enter full name")} />
                                    </div>
                                    <div className="col-md-6 mb-3">
                                        <label className="form-label">{trans("Email")}</label>
                                        <Input name="email" type="email" className="form-control" placeholder={trans("Enter email")} />
                                    </div>
                                    <div className="col-md-6 mb-3">
                                        <label className="form-label">{trans("Mobile")}</label>
                                        <Input name="mobile" className="form-control" placeholder={trans("Enter mobile number")} />
                                    </div>
                                    <div className="col-md-12 mb-3">
                                        <label className="form-label">{trans("Message (Optional)")}</label>
                                        <Input name="message" as="textarea" className="form-control" placeholder={trans("Enter your message")} rows="3" />
                                    </div>
                                </div>

                                <div className="mb-4">
                                    <label className="form-label fw-bold text-muted">
                                        {trans("Amount")}
                                    </label>
                                    <div className="input-group input-group-lg">
                                        <span className="input-group-text bg-light border-end-0">
                                            $
                                        </span>
                                        <Input
                                            name="amount"
                                            className="form-control border-start-0 ps-0 fs-4 fw-bold"
                                            placeholder="0.00"
                                            type="number"
                                        />
                                    </div>
                                    <ErrorMessage
                                        name="amount"
                                        component="div"
                                        className="text-danger mt-1 small"
                                    />
                                </div>

                                <div className="mb-4">
                                    <label className="form-label fw-bold text-muted">
                                        {trans("Select Payment Method")}
                                    </label>

                                    <div className="payment-methods">
                                        <PaymentGateway
                                            paymentMethods={paymentMethods}
                                            imagePath={imagePath}
                                            selectMethod={(method) => {
                                                if (!method) return;
                                                setFieldValue(
                                                    "method_code",
                                                    String(method.method_code ?? method.id)
                                                );
                                                if (method.currency) {
                                                    setFieldValue("currency", method.currency);
                                                }
                                            }}
                                            loading={gatewayLoading}
                                        />
                                    </div>
                                    <ErrorMessage
                                        name="method_code"
                                        component="div"
                                        className="text-danger mt-1 small"
                                    />
                                </div>

                                <div className="mb-4">
                                    <Link
                                        href="/login"
                                        className="text-decoration-none d-flex align-items-center gap-2"
                                    >
                                        <i className="las la-info-circle fs-5"></i>
                                        {trans("Log in to use Direct Debit (ACH)")}
                                    </Link>
                                </div>

                                <div className="mb-4 text-muted small">
                                    {trans("This site is protected by reCAPTCHA and the Google")}{" "}
                                    <Link href="/policy/privacy-policy">
                                        {trans("Privacy Policy")}
                                    </Link>{" "}
                                    {trans("and")}{" "}
                                    <Link href="/policy/terms-of-service">
                                        {trans("Terms of Service")}
                                    </Link>{" "}
                                    {trans("apply")}.
                                </div>

                                <div className="d-flex gap-3">
                                    <button
                                        type="button"
                                        className="btn btn-light border px-4"
                                        style={{ color: "#000" }}
                                    >
                                        {trans("Cancel")}
                                    </button>
                                    <button
                                        type="submit"
                                        className="btn btn-primary border px-4 flex-grow-1 d-flex align-items-center justify-content-center gap-2"
                                        disabled={loading}
                                    >
                                        {loading && (
                                            <span
                                                className="spinner-border spinner-border-sm"
                                                role="status"
                                                aria-hidden="true"
                                            ></span>
                                        )}
                                        {loading ? trans("Please wait...") : trans("Donate")}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </Form>
                )}
            </Formik>

            <div className="card custom--card border-0 shadow-sm mt-4">
                <div className="card-body p-4">
                    <h4 className="mb-3">{trans("Set Up Monthly Donations")}</h4>
                    <p className="text-muted mb-4">
                        {trans(
                            "You can set up automatic monthly donations and manage your donation information if you log in."
                        )}
                    </p>
                    <div className="d-flex gap-3">
                        <Link
                            href="/login"
                            className="btn btn-outline-primary d-flex align-items-center gap-2"
                        >
                            <i className="las la-sign-in-alt"></i>
                            {trans("Log In")}
                        </Link>
                        <Link
                            href="/register"
                            className="btn btn-outline-primary d-flex align-items-center gap-2"
                        >
                            <i className="las la-user-plus"></i>
                            {trans("Create Account")}
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    );
}
