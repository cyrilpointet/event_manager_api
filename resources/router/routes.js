import Home from "../pages/Home";
import UserPage from "../pages/UserPage";
import TeamPage from "../pages/TeamPage";
import NewHappeningPage from "../pages/NewHappeningPage";
import HappeningPage from "../pages/HappeningPage";
import UserHappeningsPage from "../pages/UserHappeningsPage";
import TeamHappeningsPage from "../pages/TeamHappeningsPage";
import SurveyPage from "../pages/SurveyPage";
import NewSurveyPage from "../pages/NewSurveyPage";

export const Routes = [
    {
        path: "/",
        name: "Home",
        component: Home,
        meta: { requiresAuth: true },
    },
    {
        path: "/my-happenings",
        name: "UserHappeningsPage",
        component: UserHappeningsPage,
        meta: { requiresAuth: true },
    },
    {
        path: "/account",
        name: "Account",
        component: UserPage,
        meta: { preloadUser: true },
    },
    {
        path: "/team/:id",
        name: "Team",
        component: TeamPage,
        meta: { requiresAuth: true },
    },
    {
        path: "/team-happenings/:id",
        name: "TeamHappeningsPage",
        component: TeamHappeningsPage,
        meta: { requiresAuth: true },
    },
    {
        path: "/team/:id/new-happening",
        name: "NewHappeningPage",
        component: NewHappeningPage,
        meta: { requiresAuth: true },
    },
    {
        path: "/happening/:id",
        name: "HappeningPage",
        component: HappeningPage,
        meta: { requiresAuth: true },
    },
    {
        path: "/survey/:id",
        name: "SurveyPage",
        component: SurveyPage,
        meta: { requiresAuth: true },
    },
    {
        path: "/team/:id/new-survey",
        name: "NewSurveyPage",
        component: NewSurveyPage,
        meta: { requiresAuth: true },
    },
];
