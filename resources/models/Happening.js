import * as dayjs from "dayjs";
import { HappeningMember } from "./HappeningMember";
import { Comment } from "./Comment";

export class Happening {
    constructor(rawHappening) {
        this.id = rawHappening.id;
        this.name = rawHappening.name;
        this.description = rawHappening.description;
        this.place = rawHappening.place;

        this.created_at = rawHappening.created_at;
        this.updated_at = rawHappening.updated_at;
        this.start_at = rawHappening.start_at;
        this.end_at = rawHappening.end_at;

        this.status = rawHappening.status;
        this.surveyId = rawHappening.survey_id;

        this.team = {
            id: rawHappening.team.id,
            name: rawHappening.team.name,
        };

        this.members = [];
        rawHappening.members.forEach((user) => {
            this.members.push(new HappeningMember(user));
        });

        this.comments = [];
        rawHappening.comments.forEach((comment) => {
            this.comments.push(new Comment(comment));
        });
    }

    get createdAt() {
        return dayjs(this.created_at).utc().format("DD-MM-YYYY");
    }

    get updatedAt() {
        return dayjs(this.updated_at).utc().format("DD-MM-YYYY");
    }

    get startAt() {
        return dayjs(this.start_at).utc().format("DD-MM-YYYY à HH:mm");
    }

    get endAt() {
        return dayjs(this.end_at).utc().format("DD-MM-YYYY à HH:mm");
    }
}
