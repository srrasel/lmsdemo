import LibraryBooks from "./_components/LibraryBooks";
import { getMetaTitle } from "@/lib/helpers";

export const metadata = getMetaTitle("Library");

export default function Library() {
    return (
        <>
            <LibraryBooks />
        </>
    );
}
