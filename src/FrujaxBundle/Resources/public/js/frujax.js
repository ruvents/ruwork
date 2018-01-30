(function ($) {
    'use strict'

    /**
     * Constructor
     */

    function Frujax(element) {
        this.$element = $(element)
        this._options = {}
        this._locked = false
    }

    /**
     * Constants
     */

    Frujax.DATA_SELECTOR = '[data-frujax]'
    Frujax.EVENT_NAMESPACE = '.frujax'
    Frujax.INTERNAL_EVENT_NAMESPACE = Frujax.EVENT_NAMESPACE + '.internal'

    /**
     * Public Methods
     */

    Frujax.prototype.options = function (options) {
        if ('undefined' === typeof options || true === options) {
            return this._getResolvedOptions()
        } else if (false === options) {
            return this._options
        } else if ('object' === typeof options) {
            $.extend(this._options, options)

            var resolvedOptions = this._getResolvedOptions()

            this._rebindInternalEvents(resolvedOptions)

            this._trigger('options', [this._options], 'options', resolvedOptions)

            return
        }

        throw new Error('Frujax: object expected.')
    }

    Frujax.prototype.init = function () {
        var options = this._getResolvedOptions()

        this._rebindInternalEvents(options)

        if (options.autoload) {
            this.fire()
        }
    }

    Frujax.prototype.fire = function (opts) {
        if (this._locked) {
            return
        }

        this._locked = true

        var _this = this,
            options = $.extend({}, this._getResolvedOptions(), opts),
            ajaxSettings = this._getAjaxSettings(options)

        var deferred = $.Deferred()
            .done(function () {
                var jqXHR

                if (_this.$element.is('form')) {
                    jqXHR = _this.$element.ajaxSubmit(ajaxSettings).data('jqxhr')
                } else {
                    jqXHR = $.ajax(ajaxSettings)
                }

                jqXHR
                    .done(function ($data, textStatus, jqXHR) {
                        if (jqXHR.getResponseHeader('Frujax-Redirect-Url')) {
                            textStatus = 'redirect'
                        } else if (options.action && options.target) {
                            setTimeout(function () {
                                _this._performAction(options.action, options.target, $data)
                            }, 1)
                        }

                        _this._triggerAll(
                            [textStatus, 'done', 'always'],
                            [jqXHR, textStatus, options, $data],
                            'jqXHR, textStatus, options, $data',
                            options
                        )

                        if (options.history) {
                            _this._pushToHistory(jqXHR, options)
                        }
                    })
                    .fail(function (jqXHR, textStatus) {
                        _this._triggerAll(
                            ['fail', 'always', textStatus],
                            [jqXHR, textStatus, options],
                            'jqXHR, textStatus, options',
                            options
                        )
                    })
                    .always(function () {
                        _this._locked = false
                    })
            })
            .fail(function () {
                _this._locked = false
            })

        this._trigger('start', [deferred, options], 'deferred, options', options)

        deferred.resolve()
    }

    //noinspection JSUnusedGlobalSymbols
    Frujax.prototype.locked = function (locked) {
        if ('undefined' === typeof locked) {
            return this._locked
        }

        if ('boolean' !== typeof locked) {
            throw new Error('Frujax: boolean expected.')
        }

        this._locked = locked
    }

    //noinspection JSUnusedGlobalSymbols
    Frujax.prototype.destroy = function () {
        this._unbindInternalEvents()
    }

    /**
     * Private Methods
     */

    Frujax.prototype._rebindInternalEvents = function (options) {
        this._unbindInternalEvents()

        var _this = this,
            events = options.events

        if (!events) {
            if (this.$element.is('form')) {
                events = 'submit'
            } else if (this.$element.is('a, :button')) {
                events = 'click'
            }
        }

        if (!events) {
            return
        }

        events = this._getNamespacedEvents(events, Frujax.INTERNAL_EVENT_NAMESPACE)

        this.$element.on(events, function (event) {
            if (options.preventDefault) {
                event.preventDefault()
            }

            _this.fire()
        })
    }

    Frujax.prototype._performAction = function (action, target, $data) {
        target = this._evaluateElement(target)

        if (!target.length) {
            return
        }

        if ('fill' === action) {
            target.html($data)
        } else if ('replace' === action) {
            target.replaceWith($data)
        } else if ('append' === action) {
            target.append($data)
        } else if ('prepend' === action) {
            target.prepend($data)
        } else if ('after' === action) {
            target.after($data)
        } else if ('before' === action) {
            target.before($data)
        } else if ('fire' === action) {
            target.frujax('fire')
        } else {
            throw new Error('Frujax: invalid action.')
        }
    }

    Frujax.prototype._pushToHistory = function (jqXHR, options) {
        window.history.pushState(
            'frujax',
            jqXHR.getResponseHeader('Frujax-Title'),
            jqXHR.getResponseHeader('Frujax-Request-Url') || options.url
        )
    }

    Frujax.prototype._unbindInternalEvents = function () {
        this.$element.off(Frujax.INTERNAL_EVENT_NAMESPACE)
    }

    Frujax.prototype._getResolvedOptions = function () {
        var options = this._options

        if (options.inheritOptionsOf) {
            var parent = this._evaluateElement(options.inheritOptionsOf)

            if (0 === parent.length) {
                throw new Error('Frujax: cannot inherit options from an empty element.')
            }

            if (1 < parent.length) {
                throw new Error('Frujax: cannot inherit options from multiple elements.')
            }

            var parentOptions = parent.frujax('options')

            delete parentOptions.events
            delete parentOptions.actionEvents

            options = options.inheritOptionsRecursively
                ? $.extend(true, {}, parentOptions, options)
                : $.extend({}, parentOptions, options)
        }

        options.url = options.url || this.$element.attr('href') || this.$element.attr('action') || ''
        options.method = options.method || this.$element.attr('method') || 'GET'
        options.headers = $.extend({}, options.headers || {}, {'Frujax': true})

        if ('undefined' === typeof options.history && this.$element.is('a, form')) {
            options.history = true
        }

        if (options.interceptRedirect) {
            options.headers['Frujax-Intercept-Redirect'] = true
        }

        return options
    }

    Frujax.prototype._getAjaxSettings = function (options) {
        return {
            context: this.$element,
            converters: {
                'text html': function (data) {
                    var $data = $(data)

                    if (options.autowire) {
                        setTimeout(function () {
                            $data.find(Frujax.DATA_SELECTOR).addBack(Frujax.DATA_SELECTOR).frujax()
                        }, 100)
                    }

                    return $data
                }
            },
            data: options.data,
            dataType: 'html',
            headers: options.headers,
            method: options.method,
            timeout: options.timeout,
            url: options.url
        }
    }

    Frujax.prototype._triggerAll = function (events, args, argsString, options) {
        var _this = this

        events.forEach(function (event) {
            _this._trigger(event, args, argsString, options)
        })
    }

    Frujax.prototype._trigger = function (event, args, argsString, options) {
        var callbackName = this._getCallbackName(event),
            optionsCallback = options[callbackName],
            globalCallbacks = $.fn.frujax.callbacks[callbackName]

        this.$element.trigger(event + Frujax.EVENT_NAMESPACE, args)

        if (optionsCallback) {
            this._evaluateCallback(optionsCallback, args, argsString)
        }

        if (globalCallbacks) {
            if ('object' !== typeof globalCallbacks) {
                throw new Error('Frujax: each global callbacks group must be a collection.')
            }

            for (var key in globalCallbacks) if (globalCallbacks.hasOwnProperty(key)) {
                this._evaluateCallback(globalCallbacks[key], args, argsString)
            }
        }
    }

    Frujax.prototype._evaluateCallback = function (expression, args, argsString, context) {
        if ('function' !== typeof expression && 'string' !== typeof expression) {
            throw new Error('Frujax: string or function expected.')
        }

        if ('string' === typeof expression) {
            expression = new Function(argsString, expression)
        }

        return expression.apply(context || this.$element, args)
    }

    Frujax.prototype._evaluateElement = function (expression, args, argsString, context) {
        if ('function' !== typeof expression && 'string' !== typeof expression) {
            throw new Error('Frujax: string or function expected.')
        }

        if ('string' === typeof expression) {
            try {
                return $(expression)
            } catch (e) {
                expression = new Function(argsString, 'return ' + expression)
            }
        }

        return expression.apply(context || this.$element, args)
    }

    Frujax.prototype._getNamespacedEvents = function (events, namespace) {
        var getNamespacedEvents = ''

        events.split(/ +/).forEach(function (event) {
            if (event) {
                getNamespacedEvents += event + namespace + ' '
            }
        })

        return $.trim(getNamespacedEvents)
    }

    Frujax.prototype._getCallbackName = function (string) {
        return 'on' + string.charAt(0).toUpperCase() + string.slice(1)
    }

    /**
     * jQuery Plugin
     */

    $.fn.frujax = function () {
        var method = arguments[0],
            args = Array.prototype.slice.call(arguments, 1),
            result = this

        this.each(function () {
            var $element = $(this),
                data = $element.data(),
                frujaxObj = data.frujaxObj

            if (!method || 'object' === typeof method) {
                if (!frujaxObj) {
                    $element.data('frujaxObj', (frujaxObj = new Frujax(this)))
                    frujaxObj.options($.extend({},
                        $.fn.frujax.defaults,
                        function () {
                            var names = ['action', 'autoload', 'events', 'history', 'target', 'url'],
                                options = {}

                            names.forEach(function (name) {
                                options[name] = data[name]
                            })

                            return options
                        }(),
                        data.frujax || {},
                        method || {}
                    ))
                    frujaxObj.init()
                }

                return
            }

            if (!frujaxObj) {
                throw new Error('Frujax: plugin is not initialized on this element.')
            }

            if ('string' !== typeof method || !frujaxObj[method] || '_' === method.charAt(0)) {
                throw new Error('Frujax: ivalid method call.')
            }

            var methodResult = frujaxObj[method].apply(frujaxObj, args)

            if ('destroy' === method) {
                $element.data('frujaxObj', null)
            }

            if ('undefined' !== typeof methodResult) {
                result = methodResult
            }
        })

        return result
    }

    /**
     * jQuery Plugin Defaults
     */

    $.fn.frujax.defaults = {
        action: 'fill',
        actionEvents: 'done',
        autoload: false,
        autowire: true,
        data: {},
        events: undefined,
        headers: {},
        history: undefined,
        historyEvents: 'done',
        inheritOptionsOf: null,
        inheritOptionsRecursively: true,
        interceptRedirect: false,
        method: undefined,
        preventDefault: true,
        target: null,
        timeout: 10000,
        url: undefined
    }

    /**
     * Global Callbacks
     */

    $.fn.frujax.callbacks = {}

    /**
     * History PopState Event
     */

    $(window).on('popstate', function (event) {
        if ('frujax' === event.originalEvent.state) {
            window.location.reload()
        }
    })

    /**
     * Automatic Initialization
     */

    $(document).ready(function () {
        $(Frujax.DATA_SELECTOR).frujax()
    })
})(window.jQuery)
