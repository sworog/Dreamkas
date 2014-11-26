package ru.dreamkas.pos.espresso.testSuites;

import android.view.View;
import android.widget.LinearLayout;

import com.google.android.apps.common.testing.ui.espresso.ViewInteraction;
import com.google.android.apps.common.testing.ui.espresso.matcher.BoundedMatcher;

import org.hamcrest.Description;
import org.hamcrest.Matcher;

import java.util.ArrayList;
import java.util.List;

import ru.dreamkas.pos.R;
import ru.dreamkas.pos.espresso.ScreenshotFailureHandler;
import ru.dreamkas.pos.espresso.steps.CommonSteps;
import ru.dreamkas.pos.espresso.steps.DrawerSteps;
import ru.dreamkas.pos.espresso.steps.KasSteps;
import ru.dreamkas.pos.espresso.steps.KasThen;
import ru.dreamkas.pos.espresso.steps.LoginSteps;
import ru.dreamkas.pos.espresso.steps.StoreSteps;
import ru.dreamkas.pos.model.DrawerMenu;
import ru.dreamkas.pos.model.api.Product;
import ru.dreamkas.pos.view.activities.LoginActivity_;

import static com.google.android.apps.common.testing.ui.espresso.Espresso.onData;
import static com.google.android.apps.common.testing.ui.espresso.Espresso.onView;
import static com.google.android.apps.common.testing.ui.espresso.Espresso.setFailureHandler;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.click;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.matches;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isDisplayed;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withContentDescription;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withId;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withText;
import static com.google.android.apps.common.testing.ui.espresso.util.TreeIterables.breadthFirstViewTraversal;
import static org.hamcrest.Matchers.startsWith;
import static org.hamcrest.core.IsAnything.anything;
import static org.hamcrest.core.IsNot.not;
import static org.hamcrest.object.HasToString.hasToString;
import static ru.dreamkas.pos.espresso.EspressoHelper.has;
import static ru.dreamkas.pos.espresso.EspressoHelper.waitForData;
import static ru.dreamkas.pos.espresso.EspressoHelper.waitForView;
import static ru.dreamkas.pos.espresso.EspressoHelper.withProduct;

public class Us113_3 extends BaseTestSuite<LoginActivity_> {
    @SuppressWarnings("deprecation")
    public Us113_3()
    {
        // This constructor was deprecated - but we want to support lower API levels.
        super("ru.crystals.vaverjanov.dreamkas.view", LoginActivity_.class);
    }

    //Scenario: Поиск товара по части артикулу
    public void testSearchBySku() throws Throwable {
        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.selectStore("Магазин №2");
        KasSteps.search("100");

        //waitForData(withProduct("Товар1"), R.id.lvProductsSearchResult, 10000);

        ArrayList<Product> ethalon = new ArrayList<Product>(){{
            add(new Product(){{setName("Товар1"); setSku("10001"); setBarcode("111111111");}});
            add(new Product(){{setName("Вар2"); setSku("10002"); setBarcode("22222222");}});
            add(new Product(){{setName("Товар3"); setSku("10003"); setBarcode("33333333");}});
            add(new Product(){{setName("Товар без цены продажи"); setSku("10004"); setBarcode("666666");}});
        }};

        KasThen.checkSearchProductResult(4, ethalon);
    }

    //Scenario: Поиск товара по названию
    public void testSearchByTitle() throws Throwable {

        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.selectStore("Магазин №2");
        KasSteps.search("Товар");

        //waitForData(withProduct("Товар1"), R.id.lvProductsSearchResult, 10000);

        ArrayList<Product> ethalon = new ArrayList<Product>(){{
            add(new Product(){{setName("Товар1"); setSku("10001"); setBarcode("111111111");}});
            add(new Product(){{setName("Товар3"); setSku("10003"); setBarcode("33333333");}});
        }};

        KasThen.checkSearchProductResult(3, ethalon);
    }

    //Scenario: Поиск товара по штрих-коду
    public void testSearchByBarcode() throws Throwable {

        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.selectStore("Магазин №2");
        KasSteps.search("2222");

        //waitForData(withProduct("Вар2"), R.id.lvProductsSearchResult, 10000);

        ArrayList<Product> ethalon = new ArrayList<Product>(){{
            add(new Product(){{setName("Вар2"); setSku("10002"); setBarcode("22222222");}});
        }};

        KasThen.checkSearchProductResult(1, ethalon);
    }

    //Scenario: Проверка сообщения автокомплита о том, что надо ввести 3 или более символа
    public void testSearchResultMessageTooShortQuery() throws Throwable {

        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.selectStore("Магазин №2");
        KasSteps.search("qw", false);

        waitForView("Для поиска товара введите 3 или более символа.", 10000);
    }

    //Scenario: Проверка сообщения автокомплита о том, что надо ввести 3 или более символа
    public void testSearchResultEmpty() throws Throwable {

        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.selectStore("Магазин №2");
        KasSteps.search("Такого товара нет");

        waitForView("Продуктов не найдено.", 10000);
    }

    //Scenario: Проверка представления товара без штрихкода
    public void testSearchResultWithoutBarcode() throws Exception {
       //todo impl
    }
}
