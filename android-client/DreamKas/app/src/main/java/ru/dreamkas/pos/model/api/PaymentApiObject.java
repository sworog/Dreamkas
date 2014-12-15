package ru.dreamkas.pos.model.api;

import android.support.annotation.Nullable;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import com.fasterxml.jackson.annotation.JsonInclude;

@JsonInclude(JsonInclude.Include.NON_EMPTY)
@JsonIgnoreProperties(ignoreUnknown=true)
public class PaymentApiObject {
    private String type;
    private Double amountTendered;

    @Nullable
    private Double change;

    public PaymentApiObject() {
        type = "bankcard";
    }

    public PaymentApiObject(double amountTendered) {
        this.amountTendered = amountTendered;
        type = "cash";
    }

    public String getType() {
        return type;
    }

    public Double getAmountTendered() {
        return amountTendered;
    }

    public Double getChange() {
        return change;
    }
}
