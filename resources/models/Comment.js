import dayjs from "dayjs";

export class Comment {
    constructor(rawComment) {
        this.id = rawComment.id;
        this.created_at = rawComment.created_at;
        this.content = rawComment.content;
        this.user = {
            id: rawComment.user.id,
            name: rawComment.user.name,
            email: rawComment.user.email,
        };
    }

    get createdAt() {
        return dayjs(this.created_at).format("DD-MM-YYYY à HH:mm");
    }
}
