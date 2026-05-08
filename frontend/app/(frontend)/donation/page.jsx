import DonationForm from "./_components/DonationForm";
import { getMetaTitle } from "@/lib/helpers";

export const metadata = getMetaTitle("Donation");

export default function Donation() {
    return (
        <section className="py-50">
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-lg-8">
                        <DonationForm />
                    </div>
                </div>
            </div>
        </section>
    );
}
