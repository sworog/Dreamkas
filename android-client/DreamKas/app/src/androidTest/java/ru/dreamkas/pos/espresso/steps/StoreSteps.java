package ru.dreamkas.pos.espresso.steps;

import ru.dreamkas.pos.R;
import ru.dreamkas.pos.espresso.testSuites.BaseTestSuite;

import static com.google.android.apps.common.testing.ui.espresso.Espresso.onView;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.clearText;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.click;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.closeSoftKeyboard;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.typeText;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isDisplayed;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withId;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withText;
import static ru.dreamkas.pos.espresso.EspressoHelper.waitForView;

public class StoreSteps {
    public static void selectStore(String store) throws Throwable {
        CommonSteps.clickOnViewWithId(R.id.spStores);
        CommonSteps.clickOnViewWithText(store);
        CommonSteps.clickOnViewWithId(R.id.btnSaveStoreSettings);
    }

    public static void assertStore(String store) {
        waitForView(R.id.txtProductSearchQuery, 10000, isDisplayed());
        waitForView(store, 10000);
    }
}
