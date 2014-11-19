package ru.dreamkas.pos.espresso.testSuites;

import android.test.suitebuilder.annotation.SmallTest;

import ru.dreamkas.pos.R;
import ru.dreamkas.pos.espresso.ScreenshotFailureHandler;
import ru.dreamkas.pos.espresso.steps.CommonSteps;
import ru.dreamkas.pos.espresso.steps.CommonThen;
import ru.dreamkas.pos.espresso.steps.DrawerSteps;
import ru.dreamkas.pos.espresso.steps.LoginSteps;
import ru.dreamkas.pos.espresso.steps.LoginThen;
import ru.dreamkas.pos.espresso.steps.StoreSteps;
import ru.dreamkas.pos.model.DrawerMenu;
import ru.dreamkas.pos.model.api.NamedObject;
import ru.dreamkas.pos.view.activities.LoginActivity_;

import static com.google.android.apps.common.testing.ui.espresso.Espresso.onData;
import static com.google.android.apps.common.testing.ui.espresso.Espresso.onView;
import static com.google.android.apps.common.testing.ui.espresso.Espresso.setFailureHandler;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.click;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isDisplayed;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withId;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withText;
import static org.hamcrest.CoreMatchers.instanceOf;
import static org.hamcrest.Matchers.allOf;
import static org.hamcrest.Matchers.hasToString;
import static org.hamcrest.Matchers.is;
import static org.hamcrest.text.StringStartsWith.startsWith;
import static ru.dreamkas.pos.espresso.EspressoHelper.waitForView;

public class Us113_2 extends BaseTestSuite<LoginActivity_> {
    @SuppressWarnings("deprecation")
    public Us113_2()
    {
        // This constructor was deprecated - but we want to support lower API levels.
        super("ru.crystals.vaverjanov.dreamkas.view", LoginActivity_.class);
    }

    //Scenario: Выбор магазина при первом старте
    public void testUserWillSeeKasFragmentWithSelectedStoreWhenChooseItInSpinner() throws Exception {

        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.selectStore("Магазин №2");
        StoreSteps.assertStore("Магазин №2");
    }

    //Scenario: Смена магазина
    public void testUserWillSeeKasFragmentWithSelectedStoreWhenUserHaveStoreAndSelectsNewStoreFromSpinner() throws InterruptedException {

        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.selectStore("Магазин №2");

        DrawerSteps.select(DrawerMenu.AppStates.Store);

        StoreSteps.selectStore("Магазин №1");
        StoreSteps.assertStore("Магазин №1");
    }

    //Scenario: Магазин выбирается автоматически при старте если уже был выбран заранее
    public void testUserWillSeeKasFragmentWithSelectedStoreWhenUserHaveStore() throws InterruptedException {

        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.selectStore("Магазин №2");

        CommonSteps.exitFromApp();

        Thread.sleep(2000);
        getActivity();
        waitForView(R.id.txtUsername, 10000, isDisplayed());
        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.assertStore("Магазин №2");
    }
}
