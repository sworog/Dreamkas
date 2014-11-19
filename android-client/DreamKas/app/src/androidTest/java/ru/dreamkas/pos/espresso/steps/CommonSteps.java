package ru.dreamkas.pos.espresso.steps;


import ru.dreamkas.pos.R;
import ru.dreamkas.pos.model.DrawerMenu;

import static com.google.android.apps.common.testing.ui.espresso.Espresso.onView;
import static com.google.android.apps.common.testing.ui.espresso.Espresso.pressBack;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.click;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.pressKey;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isDisplayed;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withId;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withText;
import static ru.dreamkas.pos.espresso.EspressoHelper.waitForView;

public class CommonSteps {
    public static void pressBackButton() {
        pressBack();
    }

    public static void clickOnViewWithId(int viewId) {
        waitForView(viewId, 10000, isDisplayed());
        onView(withId(viewId)).perform(click());
    }

    public static void clickOnViewWithText(String text) {

    }

    public static void exitFromApp() {
        DrawerSteps.select(DrawerMenu.AppStates.Exit);
        onView(withText("Да")).perform(click());
    }
}
