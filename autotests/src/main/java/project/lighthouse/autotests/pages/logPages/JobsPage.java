package project.lighthouse.autotests.pages.logPages;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.objects.web.log.JobLogObject;
import project.lighthouse.autotests.objects.web.log.JobLogObjectCollection;

@DefaultUrl("/logs")
public class JobsPage extends CommonPageObject {

    public static final String RECALC_PRODUCT_MESSAGE_TYPE = "recalc_product_price";
    public static final String SET10_EXPORT_PRODUCTS_TYPE = "set10_export_products";
    public static final String SUCCESS_STATUS = "success";

    public JobsPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public JobLogObjectCollection getJobLogObjectCollection() {
        return new JobLogObjectCollection(getDriver(), By.xpath("//*[@class='log__item']"));
    }

    public JobLogObject getLastRecalcProductLogMessage() {
        return getJobLogObjectCollection().getLastByType(RECALC_PRODUCT_MESSAGE_TYPE);
    }

    public JobLogObject getLastExportLogMessage() {
        return getJobLogObjectCollection().getLastByType(SET10_EXPORT_PRODUCTS_TYPE);
    }
}
