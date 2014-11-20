package ru.dreamkas.pos.unit.model;

import android.test.AndroidTestCase;
import java.math.BigDecimal;

import ru.dreamkas.pos.Constants;
import ru.dreamkas.pos.model.ReceiptItem;
import ru.dreamkas.pos.model.api.Product;
import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;
import static org.hamcrest.Matchers.not;
import static org.hamcrest.core.AllOf.allOf;

public class ReceiptItemTest extends AndroidTestCase {
    BigDecimal price = new BigDecimal(100.112).setScale(Constants.SCALE_MONEY, BigDecimal.ROUND_HALF_UP);
    BigDecimal customPrice = new BigDecimal(150.336).setScale(Constants.SCALE_MONEY, BigDecimal.ROUND_HALF_UP);
    BigDecimal quantity = new BigDecimal(3.668).setScale(Constants.SCALE_QUANTITY, BigDecimal.ROUND_HALF_UP);
    private ReceiptItem mItem;

    @Override
    protected void setUp() throws Exception {
        super.setUp();
        Product product = new Product();
        product.setSellingPrice(price);
        mItem = new ReceiptItem(product);
    }

    public void testDefaultSellingPrice() {
        assertThat("Default selling price of receiptItem should be equals to origin product's selling price", mItem.getSellingPrice(), is(price.setScale(Constants.SCALE_MONEY, BigDecimal.ROUND_HALF_UP)));
    }

    public void testCustomSellingPrice() {
        mItem.setSellingPrice(customPrice);
        assertThat("Custom selling price of receiptItem should be vary to origin product's selling price", mItem.getSellingPrice(), allOf(not(price.setScale(Constants.SCALE_MONEY, BigDecimal.ROUND_HALF_UP)), is(customPrice.setScale(Constants.SCALE_MONEY, BigDecimal.ROUND_HALF_UP))));
    }

    public void testTotal() {
        mItem.setQuantity(quantity);
        assertThat("Total should be multiply of product selling price and quantity", mItem.getTotal(), is(price.multiply(quantity).setScale(Constants.SCALE_MONEY, BigDecimal.ROUND_HALF_UP)));
    }

    public void testTotalWithCustomSellingPrice() {
        mItem.setQuantity(quantity);
        mItem.setSellingPrice(customPrice);
        assertThat("Total should be multiply of custom receipt item selling price and it's quantity", mItem.getTotal(), allOf(not(price.multiply(quantity).setScale(Constants.SCALE_MONEY, BigDecimal.ROUND_HALF_UP)), is(customPrice.multiply(quantity).setScale(Constants.SCALE_MONEY, BigDecimal.ROUND_HALF_UP))));
    }

    public void testCloneReceiptItem() {
        mItem.setQuantity(quantity);
        ReceiptItem clone = new ReceiptItem(mItem);

        assertThat("Clone selling price should be equals with origin item", clone.getSellingPrice(), is(mItem.getSellingPrice()));
        assertThat("Clone total should be equals with origin item", clone.getTotal(), is(mItem.getTotal()));
        assertThat("Clone quantity should be equals with origin item", clone.getQuantity(), is(mItem.getQuantity()));
    }
}
